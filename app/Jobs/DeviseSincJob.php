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
    public int $tries = 10000;

    public function __construct($server)
    {
        $this->server = $server;
    }
    public function backoff(): array
    {
        return [5, 10,20];
    }
    public function handle(XuiFactory $xuiFactory,ClientService $clientService): void
    {
        try {
            $devises = Devise::get();
            $xui = $xuiFactory->make($this->server);
            $xui->delAllClients($this->server);
            $clientService->createClients($this->server,$devises);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            throw $e;
        }

    }
}
