<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  ...$guards
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        // Jika tidak ada guards yang ditentukan, gunakan guard default (web)
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // User sudah login, lanjutkan request
                Auth::shouldUse($guard);
                return $next($request);
            }
        }

        // Jika belum login, redirect ke halaman login
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }
}