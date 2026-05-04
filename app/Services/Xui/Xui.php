<?php

namespace App\Services\Xui;

use App\Services\Xui\Services\ClientService;
use App\Services\Xui\Services\InboundService;
use App\Services\Xui\Services\ServersService;

class Xui
{
    public ClientService $clients;
    public InboundService $inbounds;
    public ServersService $server;

    public function __construct()
    {
        $client = new XuiClient();

        $this->clients = new ClientService($client);
        $this->inbounds = new InboundService($client);
        $this->servers = new ServersService($client);
    }
}
