<?php

namespace App\Services\Xui\Services;

use App\Models\Devise;
use App\Models\Server;
use App\Services\Xui\XuiFactory;
use Illuminate\Support\Collection;

class ClientService
{
    public function __construct(
        protected XuiFactory $factory,
    ) {}

    public function createClient(iterable $servers,Devise $devise): void
    {
        foreach ($servers as $server) {
            $xui = $this->factory->make($server);

            $xui->createClient($server, $devise);
        }
    }
    public function createClients(Server|Collection $servers,Collection $devises): void
    {
        $servers= Collection::wrap($servers);
        foreach ($servers as $server) {
            $xui = $this->factory->make($server);
            foreach ($devises as $devise) {
                $xui->createClient($server, $devise);
            }
        }
    }

    public function deleteClient(Server|Collection $servers, Devise $devise)
    {
        $servers = Collection::wrap($servers);
        foreach ($servers as $server) {
            $xui = $this->factory->make($server);
            $xui->deleteClient($server, $devise);
        }

    }
    public function deleteClients(Server|Collection $servers,Collection $devises)
    {
        $servers = Collection::wrap($servers);
        foreach ($servers as $server) {
            foreach ($devises as $devise) {
                $xui = $this->factory->make($server);
                $xui->deleteClient($server, $devise);
            }
        }
    }

    public function clientCount(Collection $servers)
    {
        $response=[];
        foreach ($servers as $server) {
            $response[]=$this->factory->make($server)->online($server);
        }
        return $response;
    }
}
