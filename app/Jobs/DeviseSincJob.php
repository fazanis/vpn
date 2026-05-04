<?php

namespace App\Jobs;


use App\Models\Devise;
use App\Models\Server;
use App\Services\Xui\Xui;
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

    public function handle(Xui $xui): void
    {
        $devises = Devise::get();
        $xui->clients->sincClient($this->server,$devises);
    }
}
