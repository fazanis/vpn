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
        foreach ($servers as $server){
            foreach ($devises as $devise){
                $countTrafikServises->count($devise,$server);
                $this->info($devise->name);
            }
        }
    }
}
