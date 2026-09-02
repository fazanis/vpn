<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Server extends Model
{
    protected $fillable=['name','type','ip','port','folder','flag','status','imbound','login','password','token','priority'];


    public function scopeActivate($query)
    {
        return $query->where('status', 'active');
    }

    public function inbounds(): HasMany
    {
        return $this->hasMany(ServerInbound::class,'server_id','id');
    }

    public function serverLink()
    {
        return $this->type.'://'.$this->ip.':'.$this->port.'/'.$this->folder;
    }
}
