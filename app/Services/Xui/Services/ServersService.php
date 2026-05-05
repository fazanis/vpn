<?php

namespace App\Services\Xui\Services;

use App\Services\Xui\DTO\ServerStatusDTO;
use Illuminate\Support\Collection;

class ServersService extends BaseService
{
    public function status(Collection $servers)
    {
        $responses =  $this->requests('get', $servers, 'panel/api/server/status');
        return $servers->map(function ($server)use ($responses) {
            $response = $responses[$server->ip] ?? null;
            return ServerStatusDTO::fromRequest($server,$response);
        });
    }

    public function online(Collection $servers)
    {
        $responses =  $this->requests('post', $servers, 'panel/api/inbounds/onlines');
        return $servers->map(function ($server)use ($responses) {
                $response = $responses[$server->ip] ?? null;
                if ($response==null) {
                    return [
                        'sertverIp'=>$server->ip.' '.$server->name,
                        'users'=>[],
                        'count'=>0
                    ];
                }

                return [
                    'sertverIp'=>$server->ip.' '.$server->name,
                    'users'=>$response->json('obj'),
                    'count'=>count($response->json('obj') ?? [])
                ];
            });
    }

    public function cpuHistory(Collection $servers,$bucket=300):array
    {
        $history = [];
        foreach ($servers as $server){
            $history[]= $this->request('get', $server, "panel/api/server/cpuHistory/{$bucket}");
        }
        return $history;
    }
}
