<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Devise extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'user_aget',
        'ui_id',
        'ui_name',
        'del',
        'traffik'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function($model){
            $model->ui_id=str()->uuid()->toString();
            $model->ui_name=str()->slug($model->name).''. substr(Str::uuid(),0,10);
        });
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
