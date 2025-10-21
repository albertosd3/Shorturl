<?php

namespace App\Http\Controllers;

use App\Models\Click;
use App\Models\ShortUrl;
use App\Models\RotatorGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = $this->getStatistics();
        return view('dashboard.index', compact('stats'));
    }

    private function getStatistics(): array
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();

        return [
            'total_urls' => ShortUrl::count() + RotatorGroup::count(),
            'total_clicks' => Click::count(),
            'clicks_today' => Click::whereDate('created_at', $today)->count(),
            'clicks_this_week' => Click::where('created_at', '>=', $thisWeek)->count(),
            'clicks_this_month' => Click::where('created_at', '>=', $thisMonth)->count(),
            'clicks_this_year' => Click::where('created_at', '>=', $thisYear)->count(),
            
            'top_countries' => Click::select('country', DB::raw('count(*) as count'))
                ->whereNotNull('country')
                ->groupBy('country')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
                
            'top_devices' => Click::select('device', DB::raw('count(*) as count'))
                ->whereNotNull('device')
                ->groupBy('device')
                ->orderBy('count', 'desc')
                ->get(),
                
            'top_browsers' => Click::select('browser', DB::raw('count(*) as count'))
                ->whereNotNull('browser')
                ->groupBy('browser')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
                
            'daily_clicks' => Click::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
                
            'bot_percentage' => $this->getBotPercentage(),
            'blocked_percentage' => $this->getBlockedPercentage(),
        ];
    }

    private function getBotPercentage(): float
    {
        $totalClicks = Click::count();
        if ($totalClicks === 0) return 0;
        
        $botClicks = Click::where('is_bot', true)->count();
        return round(($botClicks / $totalClicks) * 100, 2);
    }

    private function getBlockedPercentage(): float
    {
        $totalClicks = Click::count();
        if ($totalClicks === 0) return 0;
        
        $blockedClicks = Click::where('is_blocked', true)->count();
        return round(($blockedClicks / $totalClicks) * 100, 2);
    }
}