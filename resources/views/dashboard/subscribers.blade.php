@extends('layouts.dashboard')

@section('title', 'Subscribers - Media World')
@section('page-title', 'Subscriber Management')

@section('content')
<!-- Search and Filters -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-8">
    <div class="flex items-center justify-between">
        <div class="flex-1 mr-4">
            <div class="relative">
                <input type="text" 
                       placeholder="Search by phone number..." 
                       class="w-full px-4 py-3 pl-12 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <i class="fas fa-search absolute left-4 top-4 text-gray-400"></i>
            </div>
        </div>
        <div class="flex space-x-4">
            <select class="px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option>All Status</option>
                <option>Active</option>
                <option>Inactive</option>
                <option>Suspended</option>
            </select>
            <button class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold">
                <i class="fas fa-download mr-2"></i>Export
            </button>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="grid md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 mb-1">Active Subscribers</p>
                <h3 class="text-3xl font-bold text-green-600">1,189</h3>
            </div>
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-3xl text-green-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 mb-1">Inactive Subscribers</p>
                <h3 class="text-3xl font-bold text-red-600">58</h3>
            </div>
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-times-circle text-3xl text-red-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 mb-1">Avg. Lifetime (Days)</p>
                <h3 class="text-3xl font-bold text-blue-600">47</h3>
            </div>
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-calendar text-3xl text-blue-600"></i>
            </div>
        </div>
    </div>
</div>

<!-- Subscribers Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="p-6 border-b">
        <h3 class="text-xl font-bold text-gray-800">
            <i class="fas fa-list text-indigo-600 mr-2"></i>
            All Subscribers
        </h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Phone Number</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Joined Date</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($subscribers as $subscriber)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-800 font-semibold">#{{ $subscriber->id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <i class="fas fa-mobile-alt text-indigo-600 mr-2"></i>
                                <span class="font-mono text-gray-800">{{ $subscriber->msisdn }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($subscriber->status === 'active')
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                                    <i class="fas fa-check-circle mr-1"></i>Active
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">
                                    <i class="fas fa-times-circle mr-1"></i>Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            {{ $subscriber->subscribed_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-6 border-t bg-gray-50">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600">
                Showing <strong>{{ $subscribers->firstItem() ?? 0 }}</strong> to 
                <strong>{{ $subscribers->lastItem() ?? 0 }}</strong> of 
                <strong>{{ $subscribers->total() }}</strong> subscribers
            </p>
            <div>
                {{ $subscribers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

