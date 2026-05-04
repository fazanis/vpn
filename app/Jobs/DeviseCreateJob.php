<?php

namespace App\Jobs;


use App\Models\Devise;
use App\Models\Server;
use App\Services\DevisesCreateServises;
use App\Services\DevisesSincServises;
use App\Services\Xui\Xui;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeviseCreateJob implements ShouldQueue
{
    use Queueable;

    public $devise;
    public function __construct(Devise $devise)
    {
        $this->devise = $devise;
    }

    public function handle(Xui $xui): void
    {
        $servers = Server::query()->get();

        $xui->clients->createClient($servers,$this->devise);
    }
}
