<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('login');
        }

        // Pastikan user masih ada di database (mencegah error if user deleted but session exists)
        $username = session('username');
        $userExists = \App\Models\User::where('username', $username)->exists();

        if (!$userExists) {
            session()->flush();
            return redirect()->route('login')->withErrors(['error' => 'Sesi Anda telah berakhir atau akun tidak ditemukan.']);
        }

        return $next($request);
    }
}
