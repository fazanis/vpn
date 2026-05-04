<?php

namespace App\Services\Xui\Services;

use App\Models\Server;
use Illuminate\Support\Collection;

class InboundService extends BaseService
{
    public function get(Collection $servers)
    {
        foreach ($servers as $server){
            return $this->request('get', $server, 'panel/api/inbounds/list');
        }
    }
    public function getOne(Server $server)
    {
        return $this->request('get', $server, 'panel/api/inbounds/list');
    }

    public function getConfigJson(Server $server)
    {
        return $this->request('get', $server, 'panel/api/server/getConfigJson');
    }
}
