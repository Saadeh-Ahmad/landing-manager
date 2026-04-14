@extends('layouts.dashboard')

@section('title', 'Dashboard - Media World')
@section('page-title', 'Dashboard Overview')

@section('content')
<!-- Stats Grid -->
<div class="grid md:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm mb-1">Total Subscribers</p>
                <h3 class="text-3xl font-bold">{{ number_format($stats['total_subscribers']) }}</h3>
            </div>
            <i class="fas fa-users text-4xl text-blue-200"></i>
        </div>
        <div class="mt-4 text-blue-100 text-sm">
            <i class="fas fa-arrow-up mr-1"></i> +12% from last month
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm mb-1">Active Today</p>
                <h3 class="text-3xl font-bold">{{ number_format($stats['active_today']) }}</h3>
            </div>
            <i class="fas fa-user-check text-4xl text-green-200"></i>
        </div>
        <div class="mt-4 text-green-100 text-sm">
            <i class="fas fa-arrow-up mr-1"></i> +8% from yesterday
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm mb-1">Revenue Today</p>
                <h3 class="text-3xl font-bold">{{ number_format($stats['revenue_today']) }} JOD</h3>
            </div>
            <i class="fas fa-dollar-sign text-4xl text-purple-200"></i>
        </div>
        <div class="mt-4 text-purple-100 text-sm">
            <i class="fas fa-arrow-up mr-1"></i> +15% from yesterday
        </div>
    </div>

    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm mb-1">New Signups</p>
                <h3 class="text-3xl font-bold">{{ number_format($stats['new_signups']) }}</h3>
            </div>
            <i class="fas fa-user-plus text-4xl text-orange-200"></i>
        </div>
        <div class="mt-4 text-orange-100 text-sm">
            <i class="fas fa-arrow-up mr-1"></i> +25% from yesterday
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid md:grid-cols-2 gap-6 mb-8">
    <!-- Subscription Trend -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-line text-indigo-600 mr-2"></i>
            Subscription Trend
        </h3>
        <canvas id="subscriptionChart" style="max-height: 250px;"></canvas>
    </div>

    <!-- Revenue Breakdown -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
            Revenue by Channel
        </h3>
        <canvas id="revenueChart" style="max-height: 250px;"></canvas>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-xl shadow-lg p-6">
    <h3 class="text-xl font-bold text-gray-800 mb-6">
        <i class="fas fa-clock text-blue-600 mr-2"></i>
        Recent Activity
    </h3>
    <div class="space-y-4">
        <div class="flex items-center justify-between border-b pb-4">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-plus text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">New Subscription</p>
                    <p class="text-sm text-gray-600">+962799123456 subscribed</p>
                </div>
            </div>
            <span class="text-sm text-gray-500">2 min ago</span>
        </div>
        
        <div class="flex items-center justify-between border-b pb-4">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Payment Received</p>
                    <p class="text-sm text-gray-600">342 JOD from daily subscriptions</p>
                </div>
            </div>
            <span class="text-sm text-gray-500">1 hour ago</span>
        </div>
        
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-times text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Unsubscribe</p>
                    <p class="text-sm text-gray-600">+962788234567 unsubscribed</p>
                </div>
            </div>
            <span class="text-sm text-gray-500">3 hours ago</span>
        </div>
    </div>
</div>

<script>
// Wait for DOM and Chart.js to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Subscription Trend Chart
    const ctx1 = document.getElementById('subscriptionChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'New Subscribers',
                data: [42, 59, 73, 67, 85, 90, 89],
                borderColor: 'rgb(79, 70, 229)',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                tension: 0.4,
                fill: true
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

    // Revenue Chart
    const ctx2 = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['DCB', 'Credit Card', 'Direct'],
            datasets: [{
                data: [65, 25, 10],
                backgroundColor: [
                    'rgb(79, 70, 229)',
                    'rgb(147, 51, 234)',
                    'rgb(59, 130, 246)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1.5,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endsection

