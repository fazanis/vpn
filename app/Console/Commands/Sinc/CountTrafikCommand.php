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


    public function handle()
    {
        $devises = \App\Models\Devise::query()->get();
        $servers = Server::get();
        try {
            $xui = new \App\Services\Xui\Xui();
            foreach ($devises as $devise){
                $traf = $xui->clients->getTrafik($servers,$devise);
                $devise->update(['trafik' => $traf]);
                sleep(0.5);
            }
        }catch (\Exception $exception){
            $this->error($exception->getMessage());
        }
    }
}
