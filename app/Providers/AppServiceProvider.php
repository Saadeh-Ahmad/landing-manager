<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set locale from session, default to Arabic
        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        } else {
            // Set default to Arabic if no session locale
            app()->setLocale('ar');
            session(['locale' => 'ar']);
        }
    }
}
