<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        $totalUsers = \App\Models\User::count();
        $totalMessages = \App\Models\Message::count();
        $unreadMessages = \App\Models\Message::where('is_read', false)->count();
        $totalSettings = \App\Models\Setting::count();

        return view('analytics.index', compact('totalUsers', 'totalMessages', 'unreadMessages', 'totalSettings'));
    }
}
