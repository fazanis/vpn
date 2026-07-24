<?php

namespace App\Console\Commands\Sinc;

use App\Models\Devise;
use App\Models\Server;
use App\Services\CountTrafikServises;
use App\Services\Xui\Services\ClientService;
use App\Services\Xui\XuiFactory;
use App\Services\XuiServices;
use Illuminate\Console\Command;

class CountTrafikCommand extends Command
{

    protected $signature = 'xui:trafik';

    protected $description = 'Command description';


    public function handle()
    {
        $servers = Server::query()->get();

        $result=[];
        foreach ($servers as $server) {
            $xui = XuiFactory::make($server);
            $response = $xui->getTraffik($server);
            foreach ($response as $id=>$value) {
                if (!isset($result[$id])) {
                    $result[$id] = 0;
                }
                $result[$id]+=$value;
            }
        }
//        dd($result);
        foreach($result as $key=>$value){
            Devise::query()->where('ui_id',$key)->update(['trafik' => $value]);
        }
    }
}
