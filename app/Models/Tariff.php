<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    protected $fillable = [
        'name',
        'price',
        'is_trial',
        'duration_days',
        'max_devices',
        ];
}
