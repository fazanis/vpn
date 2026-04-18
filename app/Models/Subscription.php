<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable=[
        'user_id',
        'type',
        'tariff_id',
        'key',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
    public function ExpiresAt()
    {
        return Carbon::parse($this->expires_at)->format('d.m.Y');
    }

    public function tariff()
    {
        return $this->hasOne(Tariff::class,'id','tariff_id');
    }
}
