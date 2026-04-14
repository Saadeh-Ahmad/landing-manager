<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Check if user is subscribed
     */
    private function checkSubscription()
    {
        if (!session('subscribed')) {
            return redirect()->route('landing')->with('error', 'Please subscribe first.');
        }
        return null;
    }

    /**
     * Display service content homepage
     */
    public function index()
    {
        if ($redirect = $this->checkSubscription()) {
            return $redirect;
        }

        return view('service.index', [
            'user_phone' => session('msisdn'),
            'subscribed_at' => session('subscribed_at'),
        ]);
    }

    /**
     * Display videos library
     */
    public function videos()
    {
        if ($redirect = $this->checkSubscription()) {
            return $redirect;
        }

        $videos = [
            ['id' => 1, 'title' => 'Action Movie', 'duration' => '2h 15m', 'thumbnail' => 'https://via.placeholder.com/300x200'],
            ['id' => 2, 'title' => 'Comedy Show', 'duration' => '45m', 'thumbnail' => 'https://via.placeholder.com/300x200'],
            ['id' => 3, 'title' => 'Documentary', 'duration' => '1h 30m', 'thumbnail' => 'https://via.placeholder.com/300x200'],
        ];

        return view('service.videos', compact('videos'));
    }

    /**
     * Display music library
     */
    public function music()
    {
        if ($redirect = $this->checkSubscription()) {
            return $redirect;
        }

        $songs = [
            ['id' => 1, 'title' => 'Summer Vibes', 'artist' => 'DJ Cool', 'duration' => '3:45'],
            ['id' => 2, 'title' => 'Night Dreams', 'artist' => 'Band XYZ', 'duration' => '4:20'],
            ['id' => 3, 'title' => 'Dance Floor', 'artist' => 'Pop Star', 'duration' => '3:15'],
        ];

        return view('service.music', compact('songs'));
    }

    /**
     * Logout and clear session
     */
    public function logout()
    {
        session()->forget(['subscribed', 'msisdn', 'transaction_id', 'subscribed_at']);
        return redirect()->route('landing')->with('success', 'Logged out successfully.');
    }
}
