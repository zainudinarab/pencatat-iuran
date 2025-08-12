<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'rt_id',
        'user_name',
        'user_role',
        'model_type',
        'model_id',
        'action',
        'old_values',
        'new_values',
        'changes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'changes'    => 'array',
    ];
}
