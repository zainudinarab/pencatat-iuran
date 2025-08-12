<?php

// app/Http/Middleware/RoleRtMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Rt;
use App\Models\Gang;

class RoleRtMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = $request->user();

        if (!$user || !$user->hasRole($role)) {
            abort(403, 'Tidak punya akses role.');
        }

        if ($role === 'bendahara') {
            $rt = Rt::where('bendahara_id', $user->id)->first();
            if (!$rt) abort(403, 'Bukan bendahara RT manapun.');
            $request->attributes->set('rt', $rt);
        }

        if ($role === 'rt') {
            $rt = Rt::where('ketua_rt_id', $user->id)->first();
            if (!$rt) abort(403, 'Bukan ketua RT manapun.');
            $request->attributes->set('rt', $rt);
        }

        if ($role === 'petugas-gang') {
            $gangs = Gang::where('ketua_gang_id', $user->id)->get();
            if ($gangs->isEmpty()) abort(403, 'Bukan petugas gang manapun.');
            $request->attributes->set('gangs', $gangs);
        }

        return $next($request);
    }
}
