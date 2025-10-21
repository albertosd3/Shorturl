<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use App\Models\RotatorGroup;
use App\Models\RotatorUrl;
use App\Models\Click;
use App\Services\StopBotService;
use App\Services\UserAgentParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UrlController extends Controller
{
    private StopBotService $stopBotService;

    public function __construct(StopBotService $stopBotService)
    {
        $this->stopBotService = $stopBotService;
    }

    public function index()
    {
        $shortUrls = ShortUrl::withCount('clicks')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        $rotatorGroups = RotatorGroup::withCount('clicks')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.urls.index', compact('shortUrls', 'rotatorGroups'));
    }

    public function createShortUrl(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'title' => 'nullable|string|max:255',
            'custom_code' => 'nullable|string|max:10|unique:short_urls,short_code',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $shortUrl = ShortUrl::create([
            'short_code' => $request->custom_code ?: ShortUrl::generateShortCode(),
            'original_url' => $request->url,
            'title' => $request->title,
            'expires_at' => $request->expires_at,
        ]);

        return response()->json([
            'success' => true,
            'short_url' => url($shortUrl->short_code),
            'short_code' => $shortUrl->short_code,
        ]);
    }

    public function createRotator(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'rotation_type' => 'required|in:sequential,random,weighted',
            'custom_code' => 'nullable|string|max:10|unique:rotator_groups,short_code',
            'urls' => 'required|array|min:2',
            'urls.*.url' => 'required|url',
            'urls.*.weight' => 'nullable|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $rotatorGroup = RotatorGroup::create([
                'name' => $request->name,
                'short_code' => $request->custom_code ?: RotatorGroup::generateShortCode(),
                'description' => $request->description,
                'rotation_type' => $request->rotation_type,
            ]);

            foreach ($request->urls as $urlData) {
                RotatorUrl::create([
                    'rotator_group_id' => $rotatorGroup->id,
                    'url' => $urlData['url'],
                    'weight' => $urlData['weight'] ?? 1,
                ]);
            }

            return $rotatorGroup;
        });

        return response()->json(['success' => true]);
    }

    public function redirect(string $shortCode)
    {
        // Try to find a short URL first
        $shortUrl = ShortUrl::where('short_code', $shortCode)
            ->where('is_active', true)
            ->first();

        if ($shortUrl) {
            if ($shortUrl->isExpired()) {
                abort(404, 'This link has expired');
            }

            $redirectUrl = $shortUrl->original_url;
            $this->trackClick($shortCode, 'short_url', $shortUrl->id);
            $shortUrl->incrementClicks();
            
            return redirect($redirectUrl);
        }

        // Try to find a rotator group
        $rotatorGroup = RotatorGroup::where('short_code', $shortCode)
            ->where('is_active', true)
            ->first();

        if ($rotatorGroup) {
            $redirectUrl = $rotatorGroup->getNextUrl();
            
            if (!$redirectUrl) {
                abort(404, 'No active URLs in this rotator');
            }

            $this->trackClick($shortCode, 'rotator', null, $rotatorGroup->id);
            $rotatorGroup->incrementClicks();
            
            return redirect($redirectUrl);
        }

        abort(404, 'Short URL not found');
    }

    private function trackClick(string $shortCode, string $clickType, ?int $shortUrlId = null, ?int $rotatorGroupId = null)
    {
        $request = request();
        $ip = $request->ip();
        $userAgent = $request->userAgent() ?? '';
        $referer = $request->header('referer');

        // Parse user agent
        $uaData = UserAgentParser::parse($userAgent);

        // Get StopBot data
        $stopBotData = $this->stopBotService->checkBlocker($ip, $userAgent, $request->url());
        $ipLookupData = $this->stopBotService->ipLookup($ip);

        Click::create([
            'short_code' => $shortCode,
            'click_type' => $clickType,
            'short_url_id' => $shortUrlId,
            'rotator_group_id' => $rotatorGroupId,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'country' => $ipLookupData['country'] ?? null,
            'city' => $ipLookupData['city'] ?? null,
            'device' => $uaData['device'],
            'browser' => $uaData['browser'],
            'os' => $uaData['os'],
            'referer' => $referer,
            'is_bot' => $stopBotData['is_bot'] ?? false,
            'is_blocked' => $stopBotData['is_blocked'] ?? false,
            'stopbot_data' => array_merge($stopBotData, $ipLookupData),
        ]);
    }

    public function toggleStatus(Request $request, string $type, int $id)
    {
        if ($type === 'short_url') {
            $model = ShortUrl::findOrFail($id);
        } elseif ($type === 'rotator') {
            $model = RotatorGroup::findOrFail($id);
        } else {
            return response()->json(['success' => false], 400);
        }

        $model->update(['is_active' => !$model->is_active]);
        
        return response()->json(['success' => true, 'is_active' => $model->is_active]);
    }
}