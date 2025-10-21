@extends('layouts.app')

@section('title', 'Dashboard - Short URL Manager')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-100">Dashboard</h1>
        <p class="text-gray-400 mt-2">Overview of your short URL analytics and statistics</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="glass-effect rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total URLs</p>
                    <p class="text-2xl font-bold text-blue-400">{{ number_format($stats['total_urls']) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="glass-effect rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total Clicks</p>
                    <p class="text-2xl font-bold text-green-400">{{ number_format($stats['total_clicks']) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="glass-effect rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Today's Clicks</p>
                    <p class="text-2xl font-bold text-purple-400">{{ number_format($stats['clicks_today']) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="glass-effect rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Bot Traffic</p>
                    <p class="text-2xl font-bold text-orange-400">{{ $stats['bot_percentage'] }}%</p>
                </div>
                <div class="w-12 h-12 bg-orange-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Daily Clicks Chart -->
        <div class="glass-effect rounded-xl p-6">
            <h3 class="text-xl font-semibold text-gray-100 mb-4">Daily Clicks (Last 30 Days)</h3>
            <canvas id="dailyClicksChart" width="400" height="200"></canvas>
        </div>

        <!-- Device Distribution -->
        <div class="glass-effect rounded-xl p-6">
            <h3 class="text-xl font-semibold text-gray-100 mb-4">Device Distribution</h3>
            <canvas id="deviceChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Tables Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top Countries -->
        <div class="glass-effect rounded-xl p-6">
            <h3 class="text-xl font-semibold text-gray-100 mb-4">Top Countries</h3>
            <div class="space-y-3">
                @foreach($stats['top_countries'] as $country)
                <div class="flex justify-between items-center py-2 border-b border-dark-700">
                    <span class="text-gray-300">{{ $country->country ?: 'Unknown' }}</span>
                    <span class="text-blue-400 font-medium">{{ number_format($country->count) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Top Browsers -->
        <div class="glass-effect rounded-xl p-6">
            <h3 class="text-xl font-semibold text-gray-100 mb-4">Top Browsers</h3>
            <div class="space-y-3">
                @foreach($stats['top_browsers'] as $browser)
                <div class="flex justify-between items-center py-2 border-b border-dark-700">
                    <span class="text-gray-300">{{ $browser->browser ?: 'Unknown' }}</span>
                    <span class="text-green-400 font-medium">{{ number_format($browser->count) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Time Period Stats -->
    <div class="mt-8 glass-effect rounded-xl p-6">
        <h3 class="text-xl font-semibold text-gray-100 mb-4">Click Statistics by Period</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-400">{{ number_format($stats['clicks_today']) }}</p>
                <p class="text-gray-400 text-sm">Today</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-green-400">{{ number_format($stats['clicks_this_week']) }}</p>
                <p class="text-gray-400 text-sm">This Week</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-purple-400">{{ number_format($stats['clicks_this_month']) }}</p>
                <p class="text-gray-400 text-sm">This Month</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-orange-400">{{ number_format($stats['clicks_this_year']) }}</p>
                <p class="text-gray-400 text-sm">This Year</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Daily Clicks Chart
    const dailyCtx = document.getElementById('dailyClicksChart').getContext('2d');
    const dailyData = @json($stats['daily_clicks']);
    
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: dailyData.map(item => new Date(item.date).toLocaleDateString()),
            datasets: [{
                label: 'Clicks',
                data: dailyData.map(item => item.count),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: '#9ca3af' },
                    grid: { color: 'rgba(75, 85, 99, 0.3)' }
                },
                x: {
                    ticks: { color: '#9ca3af' },
                    grid: { color: 'rgba(75, 85, 99, 0.3)' }
                }
            }
        }
    });

    // Device Chart
    const deviceCtx = document.getElementById('deviceChart').getContext('2d');
    const deviceData = @json($stats['top_devices']);
    
    new Chart(deviceCtx, {
        type: 'doughnut',
        data: {
            labels: deviceData.map(item => item.device || 'Unknown'),
            datasets: [{
                data: deviceData.map(item => item.count),
                backgroundColor: ['#3b82f6', '#10b981', '#8b5cf6', '#f59e0b', '#ef4444']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#9ca3af' }
                }
            }
        }
    });
</script>
@endpush
@endsection