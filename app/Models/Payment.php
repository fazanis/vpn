<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'tariff_id',
        'fk_order_id',
        'amount',
        'status',
        'intid',
        'paid_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function tarif()
    {
        return $this->belongsTo(Tariff::class,'tariff_id');
    }
}
