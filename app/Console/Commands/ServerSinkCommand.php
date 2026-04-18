<?php

namespace App\Console\Commands;

use App\Models\Server;
use App\Models\ServerInbound;
use App\Services\XuiServices;
use Illuminate\Console\Command;

class ServerSinkCommand extends Command
{
    protected $signature = 'server:sink';
    protected $description = 'Command description';
    public function handle()
    {
        $servers = Server::query()->activate()->get();
        $xui = new XuiServices();
        foreach($servers as $server){
            $imbounts =$xui->getImbounts($server)['obj'];
    
            foreach($imbounts as $imbount){
    
                $response = $xui->getImbount($server,$imbount['id']);
            
                $streamSettings= json_decode($response['obj']['streamSettings']);
                $settings= json_decode($response['obj']['settings']);
                $key='';
                $array = [
                    'server_id'=>$server->id,
                    'inbound'=>$imbount['id'],
                    'protocol'=>$response['obj']['protocol'],
                    'port'=>$response['obj']['port'],
                    'type'=>$streamSettings->network,
                    'encryption'=>$settings->encryption,
                    'security'=>$streamSettings?->security,
                    'pbk'=>$streamSettings?->realitySettings?->settings->publicKey,
                    'fp'=>$streamSettings?->realitySettings?->settings?->fingerprint,
                    'sni'=>$streamSettings?->realitySettings?->serverNames[0],
                    'sid'=>$streamSettings?->realitySettings?->shortIds[0],
                    'spx'=>$streamSettings?->realitySettings?->settings?->spiderX,
                    'pqv'=>$streamSettings?->realitySettings?->settings?->mldsa65Verify,
                ];
                
                if($streamSettings->network==="xhttp"){
                    $array =array_merge($array,[
                    'path'=>$streamSettings->xhttpSettings->path,
                    'host'=>$streamSettings?->xhttpSettings?->host,
                    'mode'=>$streamSettings?->xhttpSettings?->mode,
                    ]);
                
                }
                ServerInbound::query()->updateOrCreate([
                    'server_id'=>$server->id,
                    'inbound'=>$imbount['id'],
                ],$array);
            }
        }
    }
}
