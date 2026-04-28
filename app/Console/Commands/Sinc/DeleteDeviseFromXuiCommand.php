<?php

namespace App\Console\Commands\Sinc;

use App\Models\Devise;
use App\Models\Server;
use App\Models\ServerInbound;
use App\Services\XuiServices;
use Illuminate\Console\Command;

class DeleteDeviseFromXuiCommand extends Command
{

    protected $signature = 'xui:delete-devise';
    protected $description = 'Command description';

    public function handle()
    {
        $xuiServices=new XuiServices();
        $servers = Server::query()->get();
        foreach($servers as $server){
            foreach($xuiServices->getImbounts($server)['obj'] as $imdound){
                foreach($imdound['clientStats'] as $client){
                    $devise = Devise::query()->where('ui_id',$client['uuid'])->exists();
                    $serverInbount = ServerInbound::query()->where('inbound',$imdound['id'])->first();
//                    dd($serverInbount);
                    if(!$devise){
                        $xuiServices->deleteClientFromUiid($serverInbount,$client['uuid']);
                        $this->error($client['uuid'].' delete');
                    }
                }
            }
        }
    }
}
