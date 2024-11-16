<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Ambil role pengguna yang sedang login
        $user = Auth::user();

        // Cek apakah peran pengguna sesuai dengan salah satu peran yang diizinkan
        if (!in_array($user->level, $roles)) {
            // Jika peran tidak sesuai, tampilkan pesan error atau redirect
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
