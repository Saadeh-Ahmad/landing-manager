@extends('layouts.dashboard')

@section('title', 'Analytics - Media World')
@section('page-title', 'Analytics & Reports')

@section('content')
<!-- Date Range Selector -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-8">
    <div class="flex items-center justify-between">
        <h3 class="text-xl font-bold text-gray-800">
            <i class="fas fa-calendar-alt text-indigo-600 mr-2"></i>
            Select Date Range
        </h3>
        <div class="flex space-x-4">
            <button class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg font-semibold">Today</button>
            <button class="px-4 py-2 hover:bg-gray-100 rounded-lg">Last 7 Days</button>
            <button class="px-4 py-2 hover:bg-gray-100 rounded-lg">Last 30 Days</button>
            <button class="px-4 py-2 hover:bg-gray-100 rounded-lg">Custom</button>
        </div>
    </div>
</div>

<!-- Performance Metrics -->
<div class="grid md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-gray-600 text-sm">Conversion Rate</h4>
            <i class="fas fa-percentage text-blue-500 text-xl"></i>
        </div>
        <p class="text-3xl font-bold text-gray-800 mb-2">24.5%</p>
        <p class="text-sm text-green-600">
            <i class="fas fa-arrow-up mr-1"></i>+5.2%
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-gray-600 text-sm">Churn Rate</h4>
            <i class="fas fa-user-times text-red-500 text-xl"></i>
        </div>
        <p class="text-3xl font-bold text-gray-800 mb-2">4.2%</p>
        <p class="text-sm text-red-600">
            <i class="fas fa-arrow-down mr-1"></i>-1.1%
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-gray-600 text-sm">ARPU</h4>
            <i class="fas fa-coins text-yellow-500 text-xl"></i>
        </div>
        <p class="text-3xl font-bold text-gray-800 mb-2">1.5 JOD</p>
        <p class="text-sm text-green-600">
            <i class="fas fa-arrow-up mr-1"></i>+0.3
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-gray-600 text-sm">LTV</h4>
            <i class="fas fa-chart-line text-purple-500 text-xl"></i>
        </div>
        <p class="text-3xl font-bold text-gray-800 mb-2">70.5 JOD</p>
        <p class="text-sm text-green-600">
            <i class="fas fa-arrow-up mr-1"></i>+8.4
        </p>
    </div>
</div>

<!-- Charts -->
<div class="grid md:grid-cols-2 gap-6 mb-8">
    <!-- Daily Signups -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-6">
            <i class="fas fa-user-plus text-green-600 mr-2"></i>
            Daily Signups
        </h3>
        <canvas id="signupsChart" style="max-height: 300px;"></canvas>
    </div>

    <!-- Daily Revenue -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-6">
            <i class="fas fa-dollar-sign text-purple-600 mr-2"></i>
            Daily Revenue (JOD)
        </h3>
        <canvas id="revenueChart" style="max-height: 300px;"></canvas>
    </div>
</div>

<!-- User Engagement -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-8">
    <h3 class="text-xl font-bold text-gray-800 mb-6">
        <i class="fas fa-chart-bar text-indigo-600 mr-2"></i>
        User Engagement by Content Type
    </h3>
    <canvas id="engagementChart" style="max-height: 200px;"></canvas>
</div>

<!-- Top Performing Content -->
<div class="grid md:grid-cols-2 gap-6">
    <!-- Top Videos -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-6">
            <i class="fas fa-fire text-red-500 mr-2"></i>
            Top Videos
        </h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-red-400 to-pink-500 rounded-lg flex items-center justify-center text-white font-bold">1</div>
                    <div class="ml-4">
                        <p class="font-semibold text-gray-800">Action Movie</p>
                        <p class="text-sm text-gray-600">2,847 views</p>
                    </div>
                </div>
                <i class="fas fa-play-circle text-2xl text-red-500"></i>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-orange-400 to-red-500 rounded-lg flex items-center justify-center text-white font-bold">2</div>
                    <div class="ml-4">
                        <p class="font-semibold text-gray-800">Comedy Show</p>
                        <p class="text-sm text-gray-600">2,134 views</p>
                    </div>
                </div>
                <i class="fas fa-play-circle text-2xl text-orange-500"></i>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center text-white font-bold">3</div>
                    <div class="ml-4">
                        <p class="font-semibold text-gray-800">Documentary</p>
                        <p class="text-sm text-gray-600">1,892 views</p>
                    </div>
                </div>
                <i class="fas fa-play-circle text-2xl text-yellow-500"></i>
            </div>
        </div>
    </div>

    <!-- Top Music -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-6">
            <i class="fas fa-star text-yellow-500 mr-2"></i>
            Top Music
        </h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-teal-500 rounded-lg flex items-center justify-center text-white font-bold">1</div>
                    <div class="ml-4">
                        <p class="font-semibold text-gray-800">Summer Vibes</p>
                        <p class="text-sm text-gray-600">3,421 plays</p>
                    </div>
                </div>
                <i class="fas fa-music text-2xl text-green-500"></i>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-cyan-500 rounded-lg flex items-center justify-center text-white font-bold">2</div>
                    <div class="ml-4">
                        <p class="font-semibold text-gray-800">Night Dreams</p>
                        <p class="text-sm text-gray-600">2,987 plays</p>
                    </div>
                </div>
                <i class="fas fa-music text-2xl text-blue-500"></i>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-400 to-pink-500 rounded-lg flex items-center justify-center text-white font-bold">3</div>
                    <div class="ml-4">
                        <p class="font-semibold text-gray-800">Dance Floor</p>
                        <p class="text-sm text-gray-600">2,654 plays</p>
                    </div>
                </div>
                <i class="fas fa-music text-2xl text-purple-500"></i>
            </div>
        </div>
    </div>
</div>

<script>
// Wait for DOM and Chart.js to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Daily Signups Chart
    const ctx1 = document.getElementById('signupsChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: {!! json_encode($data['labels']) !!},
            datasets: [{
                label: 'New Signups',
                data: {!! json_encode($data['daily_signups']) !!},
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                borderColor: 'rgb(34, 197, 94)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Daily Revenue Chart
    const ctx2 = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: {!! json_encode($data['labels']) !!},
            datasets: [{
                label: 'Revenue (JOD)',
                data: {!! json_encode($data['daily_revenue']) !!},
                borderColor: 'rgb(147, 51, 234)',
                backgroundColor: 'rgba(147, 51, 234, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Engagement Chart
    const ctx3 = document.getElementById('engagementChart').getContext('2d');
    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: ['Videos', 'Music', 'Live TV', 'Games', 'Radio'],
            datasets: [{
                label: 'Engagement Hours',
                data: [1247, 892, 542, 387, 234],
                backgroundColor: [
                    'rgba(79, 70, 229, 0.8)',
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(251, 191, 36, 0.8)',
                    'rgba(168, 85, 247, 0.8)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 3.5,
            indexAxis: 'y',
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endsection

