<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\Rt;
use App\Models\Gang;

trait HasRtGangFilter
{
    public static function bootHasRtGangFilter()
    {
        static::addGlobalScope('rt_gang_filter', function (Builder $builder) {
            $user = Auth::user();
            if (!$user) return;

            if ($user->hasRole('admin')) return;

            $model = $builder->getModel();

            if ($user->hasRole('bendahara_rt')) {
                $rt = Rt::where('bendahara_id', $user->id)->first();
                $builder->where('rt_id', $rt?->id ?? 0);
            } elseif ($user->hasRole('ketua_rt')) {
                $rt = Rt::where('ketua_rt_id', $user->id)->first();
                $builder->where('rt_id', $rt?->id ?? 0);
            } elseif ($user->hasRole('petugas_rt')) {
                if ($model instanceof Gang) {
                    $builder->where('ketua_gang_id', $user->id);
                } elseif (in_array('gang_id', $model->getFillable())) {
                    $gangIds = Gang::where('ketua_gang_id', $user->id)->pluck('id');
                    $builder->whereIn('gang_id', $gangIds->count() ? $gangIds : [0]);
                } else {
                    $rt = Rt::whereHas('gangs', function ($q) use ($user) {
                        $q->where('ketua_gang_id', $user->id);
                    })->first();
                    $builder->where('rt_id', $rt?->id ?? 0);
                }
            } elseif ($user->hasRole('warga')) {
                $builder->where('user_id', $user->id);
            } else {
                $builder->whereRaw('1=0');
            }
        });
    }
}
