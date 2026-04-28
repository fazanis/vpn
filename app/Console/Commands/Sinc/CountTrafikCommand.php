<?php

namespace App\Console\Commands\Sinc;

use App\Models\Server;
use App\Services\CountTrafikServises;
use App\Services\XuiServices;
use Illuminate\Console\Command;

class CountTrafikCommand extends Command
{

    protected $signature = 'xui:trafik';

    protected $description = 'Command description';


    public function handle(CountTrafikServises $countTrafikServises)
    {
        $devises = \App\Models\Devise::query()->get();
        $xui = new XuiServices();
        $servers = Server::all();

        foreach ($devises as $devise) {
            $total = 0;
            foreach ($servers as $server) {
                $total+= $countTrafikServises->count($devise, $server);
                $this->info($devise->name);
            }
            $devise->update(['trafik'=>$total]);
        }
    }
}
