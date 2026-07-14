<?php

namespace App\Jobs;


use App\Models\Devise;
use App\Models\Server;
use App\Services\Xui\Services\ClientService;
use App\Services\Xui\Xui;
use App\Services\Xui\XuiFactory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeviseSincJob implements ShouldQueue
{
    use Queueable;
    public $server;

    public function __construct($server)
    {
        $this->server = $server;
    }

    public function handle(XuiFactory $xuiFactory,ClientService $clientService): void
    {
        $devises = Devise::get();
        $xui = $xuiFactory->make($this->server);
        $xui->delAllClients($this->server);
        $clientService->createClients($this->server,$devises);
    }
}
