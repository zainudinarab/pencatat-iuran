<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$role): Response
    {
        // Mengambil role pengguna yang sedang login
        // $user = Auth::user();
        // $user = Auth::user();

        // dd($role);
        // Jika pengguna tidak memiliki role yang sesuai, batalkan akses
        // if (!$user || !in_array($user->role, $roles)) {
        //     // Anda bisa mengalihkan pengguna ke halaman tertentu atau memberikan respons error
        //     return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        // }
        return $next($request);
    }
}
