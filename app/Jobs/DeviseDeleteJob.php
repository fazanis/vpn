<?php

namespace App\Jobs;

use App\Models\Devise;
use App\Models\Server;
use App\Services\DeviseDeleteServises;
use App\Services\Xui\Services\ClientService;
use App\Services\Xui\Xui;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeviseDeleteJob implements ShouldQueue
{
    use Queueable;
    public $devise;
    public function __construct(Devise $devise)
    {
        $this->devise = $devise;
    }

    public function handle(ClientService $service): void
    {
        $servers = Server::query()->get();
        $service->deleteClient($servers, $this->devise);
        $this->devise->delete();
    }
}
