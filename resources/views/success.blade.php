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
{{--                <p class="text-gray-600">Welcome to Media World Premium</p>--}}
            </div>

            @if(!empty($alreadySubscribed))
            <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-4 mb-6 text-left">
                <p class="text-blue-900 text-sm font-medium">{{ __('success.already_subscribed_notice') }}</p>
            </div>
            @endif

            <div class="bg-green-50 border-2 border-green-200 rounded-xl p-6 mb-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-gray-600">Status:</span>
                    <span class="text-green-600 font-bold">ACTIVE</span>
                </div>
{{--                <div class="flex items-center justify-between mb-3">--}}
{{--                    <span class="text-gray-600">Price:</span>--}}
{{--                    <span class="text-gray-800 font-bold">1 JOD/Day</span>--}}
{{--                </div>--}}
{{--                <div class="flex items-center justify-between">--}}
{{--                    <span class="text-gray-600">Access:</span>--}}
{{--                    <span class="text-gray-800 font-bold">Unlimited</span>--}}
{{--                </div>--}}
            </div>

{{--            <div class="space-y-3 mb-6">--}}
{{--                <div class="flex items-start text-left">--}}
{{--                    <i class="fas fa-check text-green-500 mt-1 mr-3"></i>--}}
{{--                    <span class="text-gray-700">Access to all premium movies & shows</span>--}}
{{--                </div>--}}
{{--                <div class="flex items-start text-left">--}}
{{--                    <i class="fas fa-check text-green-500 mt-1 mr-3"></i>--}}
{{--                    <span class="text-gray-700">Unlimited music streaming</span>--}}
{{--                </div>--}}
{{--                <div class="flex items-start text-left">--}}
{{--                    <i class="fas fa-check text-green-500 mt-1 mr-3"></i>--}}
{{--                    <span class="text-gray-700">Watch on any device</span>--}}
{{--                </div>--}}
{{--            </div>--}}

            <a href="{{ route('landing') }}"
               class="block w-full bg-gradient-to-r from-green-600 to-teal-600 text-white py-4 rounded-lg text-lg font-bold hover:shadow-2xl transform hover:scale-105 transition-all duration-200 mb-3">
                Back to Home
            </a>
        </div>

{{--        <div class="mt-6 text-center text-white text-sm">--}}
{{--            <p>You will be charged 1 JOD per day</p>--}}
{{--            <p class="mt-2">To unsubscribe, send STOP to 4089</p>--}}
{{--        </div>--}}
    </div>
</div>
@endsection

