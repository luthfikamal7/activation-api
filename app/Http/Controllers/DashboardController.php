<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SerialKey;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        $totalActive = SerialKey::where('is_used', true)
            ->where('expires_at', '>', $now)
            ->count();

        $activeThisMonth = SerialKey::whereMonth('start_at', $now->month)
            ->whereYear('start_at', $now->year)
            ->count();

        $expiredThisYear = SerialKey::whereYear('expires_at', $now->year)
            // ->where('expires_at', '<', $now)
            ->count();

        $totalExpired = SerialKey::where('expires_at', '<', $now)->where('is_used', true)->count();

        return view('dashboard.dashboard', [
            'totalActive' => $totalActive,
            'activeThisMonth' => $activeThisMonth,
            'expiredThisYear' => $expiredThisYear,
            'totalExpired' => $totalExpired,
        ]);
    }
}
