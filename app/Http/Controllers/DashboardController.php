<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscriber;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display dashboard homepage
     */
    public function index()
    {
        $totalSubscribers = Subscriber::count();
        $activeSubscribers = Subscriber::active()->count();
        
        // Active today (accessed in last 24 hours)
        $activeToday = Subscriber::where('last_active_at', '>=', Carbon::now()->subDay())->count();
        
        // Revenue today (successful transactions today)
        $revenueToday = Transaction::successful()
            ->whereDate('processed_at', Carbon::today())
            ->sum('amount');
        
        // New signups today
        $newSignups = Subscriber::whereDate('subscribed_at', Carbon::today())->count();

        $stats = [
            'total_subscribers' => $totalSubscribers,
            'active_today' => $activeToday,
            'revenue_today' => $revenueToday,
            'new_signups' => $newSignups,
        ];

        return view('dashboard.index', compact('stats'));
    }

    /**
     * Display subscribers list
     */
    public function subscribers()
    {
        $subscribers = Subscriber::orderBy('subscribed_at', 'desc')
            ->paginate(10);

        return view('dashboard.subscribers', compact('subscribers'));
    }

    /**
     * Display analytics
     */
    public function analytics()
    {
        // Get last 7 days data
        $labels = [];
        $dailySignups = [];
        $dailyRevenue = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('D');
            
            // Signups
            $signups = Subscriber::whereDate('subscribed_at', $date->format('Y-m-d'))->count();
            $dailySignups[] = $signups;
            
            // Revenue
            $revenue = Transaction::successful()
                ->whereDate('processed_at', $date->format('Y-m-d'))
                ->sum('amount');
            $dailyRevenue[] = floatval($revenue);
        }

        $data = [
            'daily_signups' => $dailySignups,
            'daily_revenue' => $dailyRevenue,
            'labels' => $labels,
        ];

        return view('dashboard.analytics', compact('data'));
    }
}
