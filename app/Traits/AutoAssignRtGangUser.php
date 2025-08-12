<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\Rt;
use App\Models\Gang;

trait AutoAssignRtGangUser
{
    public static function bootAutoAssignRtGangUser()
    {
        static::saving(function ($model) {
            $user = Auth::user();
            if (!$user) return;

            if ($user->hasRole('admin')) return;

            if ($user->hasRole('bendahara_rt') || $user->hasRole('ketua_rt')) {
                $rt = Rt::where(function ($q) use ($user) {
                    $q->where('bendahara_id', $user->id)
                        ->orWhere('ketua_rt_id', $user->id);
                })->first();

                if ($rt && !$model->rt_id) {
                    $model->rt_id = $rt->id;
                }
            }

            if ($user->hasRole('petugas_rt')) {
                $gang = Gang::where('ketua_gang_id', $user->id)->first();

                if ($gang && isset($model->gang_id) && empty($model->gang_id)) {
                    $model->gang_id = $gang->id;
                }

                if ($gang && !$model->rt_id) {
                    $model->rt_id = $gang->rt_id;
                }
            }

            if ($user->hasRole('warga')) {
                if (isset($model->user_id) && empty($model->user_id)) {
                    $model->user_id = $user->id;
                }
            }
        });
    }
}
