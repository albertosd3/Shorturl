<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StopBotService
{
    private string $apiKey;
    private string $blockerUrl;
    private string $ipLookupUrl;

    public function __construct()
    {
        $this->apiKey = config('services.stopbot.api_key', env('STOPBOT_API_KEY'));
        $this->blockerUrl = config('services.stopbot.blocker_url', env('STOPBOT_BLOCKER_URL'));
        $this->ipLookupUrl = config('services.stopbot.iplookup_url', env('STOPBOT_IPLOOKUP_URL'));
    }

    public function checkBlocker(string $ip, string $userAgent, string $url): array
    {
        try {
            $response = Http::timeout(5)->get($this->blockerUrl, [
                'apikey' => $this->apiKey,
                'ip' => $ip,
                'ua' => $userAgent,
                'url' => $url,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'is_blocked' => $data['block'] ?? false,
                    'is_bot' => $data['bot'] ?? false,
                    'reason' => $data['reason'] ?? null,
                    'data' => $data,
                ];
            }

            Log::warning('StopBot Blocker API failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return ['success' => false, 'is_blocked' => false, 'is_bot' => false];

        } catch (\Exception $e) {
            Log::error('StopBot Blocker API error', ['error' => $e->getMessage()]);
            return ['success' => false, 'is_blocked' => false, 'is_bot' => false];
        }
    }

    public function ipLookup(string $ip): array
    {
        try {
            $response = Http::timeout(5)->get($this->ipLookupUrl, [
                'apikey' => $this->apiKey,
                'ip' => $ip,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'country' => $data['country'] ?? null,
                    'city' => $data['city'] ?? null,
                    'region' => $data['region'] ?? null,
                    'timezone' => $data['timezone'] ?? null,
                    'isp' => $data['isp'] ?? null,
                    'data' => $data,
                ];
            }

            Log::warning('StopBot IP Lookup API failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return ['success' => false];

        } catch (\Exception $e) {
            Log::error('StopBot IP Lookup API error', ['error' => $e->getMessage()]);
            return ['success' => false];
        }
    }
}