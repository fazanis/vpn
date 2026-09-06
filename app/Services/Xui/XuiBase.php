<?php

namespace App\Services\Xui;

use App\Models\Devise;
use App\Models\Server;
use App\Services\Xui\DTO\ServerStatusDTO;
use Illuminate\Support\Collection;

abstract class XuiBase
{
    public function __construct(
        protected HttpClient $http,
    ){}
    public function getInbounds(Server $server){
        return $this->http->request(
            'GET',
            $server,
            'panel/api/inbounds/list'
        );
    }
    public function ping(Server $server){
        $response= $this->http->request(
            'GET',
            $server,
            'panel/api/server/status'
        );
        return $response ? $response->status() : null;
    }
    public function status(Server $server){
        $response= $this->http->request(
            'GET',
            $server,
            'panel/api/server/status'
        );
        return ServerStatusDTO::fromRequest($server,$response);
    }



    public function createClients(Server $server,Devise $devise){}
    public function delAllClients(Server $server){}
}
