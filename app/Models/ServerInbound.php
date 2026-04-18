<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerInbound extends Model
{
    protected $fillable = [
        'inbound',
        'server_id',
        'protocol',
        'port',
        'type',
        'encryption',
        'security',
        'pbk',
        'fp',
        'sni',
        'sid',
        'spx',
        'pqv',
        'path',
        'host',
        'mode',
    ];

    public function server()
    {
        return $this->belongsTo(Server::class,'server_id','id');
    }
}
