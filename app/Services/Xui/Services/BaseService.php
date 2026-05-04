<?php

namespace App\Services\Xui\Services;

use App\Models\Server;
use App\Services\Xui\XuiClient;
use Illuminate\Support\Collection;

abstract class BaseService
{
    public function __construct(protected XuiClient $client)
    {
    }

    protected function requests(string $method, Collection $servers, string $uri, array $data = [])
    {
        return $this->client->poolRequest($method, $servers, $uri, $data);
    }
    protected function request(string $method, Server $server, string $uri, array $data = [])
    {
        return $this->client->request($method, $server, $uri, $data);
    }
}
