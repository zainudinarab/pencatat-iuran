<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

trait HasActivityLog
{
    public static function bootHasActivityLog()
    {
        static::created(function ($model) {
            self::logActivity('created', $model, null, null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $changes = $model->getChanges();
            unset($changes['updated_at']);

            $oldValues = collect($model->getOriginal())->only(array_keys($changes))->toArray();
            $newValues = collect($model->getAttributes())->only(array_keys($changes))->toArray();

            self::logActivity('updated', $model, $changes, $oldValues, $newValues);
        });

        static::deleted(function ($model) {
            self::logActivity('deleted', $model, null, $model->getOriginal(), null);
        });
    }

    protected static function logActivity($action, $model, $changes = null, $oldValues = null, $newValues = null)
    {
        $user = Auth::user();
        if (!$user) return;

        ActivityLog::create([
            'rt_id' => $model->rt_id ?? null,
            'user_name'  => $user->name,
            'user_role'  => implode(',', $user->getRoleNames()->toArray()),
            'model_type' => get_class($model),
            'model_id'   => $model->id,
            'action'     => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'changes'    => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);
    }
}
