<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'telegram_id',
        'is_admin',
        'ui_id',
        'provider_id',
        'provider_name',
        'provider_token',
        'provider_refresh_token',
        'referral_code',
        'referred_by',
        // 'token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    public static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            if (!$user->referral_code) {
                $user->referral_code = Str::upper(Str::random(10));
            }
        });
        static::creating(function($model){
            $model->ui_id=str()->uuid()->toString();
        });
    }
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
 
    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }
    public function subscription()
    {
        return $this->hasOne(Subscription::class,'user_id','id');
    }
}
