<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - Media World')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-indigo-600 to-indigo-800 text-white">
            <div class="p-6">
                <h1 class="text-2xl font-bold">Media World</h1>
                <p class="text-sm text-indigo-200">Admin Dashboard</p>
            </div>
            <nav class="mt-6">
                <a href="{{ route('dashboard.index') }}" class="flex items-center px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('dashboard.index') ? 'bg-indigo-700 border-l-4 border-white' : '' }}">
                    <i class="fas fa-home mr-3"></i> Dashboard
                </a>
                <a href="{{ route('dashboard.subscribers') }}" class="flex items-center px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('dashboard.subscribers') ? 'bg-indigo-700 border-l-4 border-white' : '' }}">
                    <i class="fas fa-users mr-3"></i> Subscribers
                </a>
                <a href="{{ route('dashboard.analytics') }}" class="flex items-center px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('dashboard.analytics') ? 'bg-indigo-700 border-l-4 border-white' : '' }}">
                    <i class="fas fa-chart-line mr-3"></i> Analytics
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <header class="bg-white shadow-sm">
                <div class="px-8 py-4 flex justify-between items-center">
                    <h2 class="text-2xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-4">Admin User</span>
                        <i class="fas fa-user-circle text-3xl text-gray-600"></i>
                    </div>
                </div>
            </header>

            <main class="p-8">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>

