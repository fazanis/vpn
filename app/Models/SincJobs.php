<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SincJobs extends Model
{
    protected $fillable = [
        'command',
        'status',
        'entity_id'
    ];

    public function device()
    {
        return $this->hasOne(Devise::class,'id','entity_id');
    }
}
