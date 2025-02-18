<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;



class SetUserRTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = Auth::user();
        // dd($user);
        // if (!$user) {
        //     return $next($request);
        // }

        // // Jika user adalah Ketua RT
        // if ($user->rtKetua) {
        //     session(['rt_id' => $user->rtKetua->id]);
        // }
        // // Jika user adalah Bendahara RT
        // elseif ($user->rtBendahara) {
        //     session(['rt_id' => $user->rtBendahara->id]);
        // }
        // // Jika user adalah Petugas RT (Ketua Gang)
        // elseif ($user->gang) {
        //     session(['rt_id' => $user->gang->rt_id]);
        // }
        // // Jika user bukan pengurus RT
        // else {
        //     session()->forget('rt_id');
        // }

        return $next($request);
    }
}
