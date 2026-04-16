@extends('layouts.app')

@section('title', 'Subscription Activated - Media World')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-500 via-teal-600 to-blue-600 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-2xl p-8 text-center">
            <div class="mb-6">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-green-100 rounded-full mb-4">
                    <i class="fas fa-check-circle text-6xl text-green-500"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Subscription Activated!</h1>
            </div>

            @if(!empty($alreadySubscribed))
            <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-4 mb-6 text-left">
                <p class="text-blue-900 text-sm font-medium">{{ __('success.already_subscribed_notice') }}</p>
            </div>
            @endif

            <div class="bg-green-50 border-2 border-green-200 rounded-xl p-6 mb-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-gray-600">Status</span>
                    <span class="text-green-600 font-bold">ACTIVE</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

