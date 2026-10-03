<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_NOT_PAYMENT = 2;
    protected $fillable=[
        'user_id',
        'type',
        'tariff_id',
        'key',
        'status',
        'max_devices',
        'days',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
    public function ExpiresAt()
    {
        return Carbon::parse($this->expires_at)->format('d.m.Y');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tariff()
    {
        return $this->hasOne(Tariff::class,'id','tariff_id');
    }

    public function tarif()
    {
        return $this->hasOne(Tariff::class,'id','tariff_id');
    }

    public function isNotTrial()
    {
        return $this->where('type','pay')->exists();
    }
    public function statusName()
    {
        return match ($this->status) {
            self::STATUS_INACTIVE => ['name'=>'Просрочен. Выберите новую подписку','class'=>'end'],
            self::STATUS_ACTIVE => ['name'=>'Активна','class'=>'active'],
            self::STATUS_NOT_PAYMENT => ['name'=>'Не оплачен','class'=>'notpay'],
            default => ['name'=>'Не оплачен','class'=>'notpay'],
        };
    }
}
