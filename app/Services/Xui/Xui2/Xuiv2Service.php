<?php

namespace App\Services\Xui\Xui2;

use App\Models\Devise;
use App\Models\Server;
use App\Services\Xui\DTO\ServerStatusDTO;
use App\Services\Xui\HttpClient;
use App\Services\Xui\XuiBase;
use Illuminate\Support\Collection;

class Xuiv2Service extends XuiBase
{

    protected function deviseEmail($inbound, $devise)
    {
        return $devise->ui_name .'_'.$inbound->inbound;
    }
    public function createClient(Server $server,Devise $devise)
    {
        foreach ($server->inbounds as $inbound) {
            $clients = [];
                $clients[] = [
                    'id' => $devise->ui_id,
                    'flow' => "",
                    'email' => (string)$this->deviseEmail($inbound, $devise),
                    'limitIp' => 0,
                    'totalGB' => 0,
                    'expiryTime' => 0,
                    'enable' => true,
                    'tgId' => "",
                    'subId' => str()->uuid()->toString(),
                    'comment' => "",
                    'reset' => 0,
                ];

            $data = [
                'id' => $inbound->inbound,
                'settings' => json_encode([
                    'clients' => $clients
                ])
            ];

            try {
                $response = $this->http->request('post', $server, 'panel/api/inbounds/addClient', $data);
            } catch (\Exception $exception) {
                dump($exception->getMessage());
            }

        }
    }

    public function deleteClient(Server $server,Devise $devise)
    {
        foreach ($server->inbounds as $inbound) {
            $this->http->request('post', $server, "panel/api/inbounds/{$inbound->inbound}/delClientByEmail/{$this->deviseEmail($inbound, $devise)}");
        }

    }

    public function delAllClients(Server $server)
    {
        $r = $this->http->request('get', $server, 'panel/api/inbounds/list');
        foreach($r->json('obj') as $client){
            $id=$client['id'];
            $client = json_decode($client['settings']);
              foreach (array_reverse($client->clients) as $item){
                  $r = $this->http->request('post', $server, "panel/api/inbounds/{$id}/delClientByEmail/{$item->email}");
              }

        }
    }
    public function subLink(Server $server,Devise $devise)
    {
        $array = [];
        foreach($server->inbounds as $connect){
            $url='';
            $url.=$connect->protocol
                .'://'.$devise->ui_id
                .'@'.$connect->server->ip
                .':'.$connect->port
                .'?type='.$connect->type
                .'&encryption='.$connect->encryption;
            if ($connect->type==='xhttp'){
                $url.='&path='.$connect->path;
                $url.='&host='.$connect->host;
                $url.='&mode='.$connect->mode;
            }
            $url.='&security='.$connect->security;
            $url.='&pbk='.$connect->pbk;
            $url.='&fp='.$connect->fp;
            $url.='&sni='.$connect->sni;
            $url.='&sid='.$connect->sid;
            $url.='&spx='.$connect->spx;
            $url.='&pqv='.$connect->pqv;
            $url.='#'.$connect->server->name;
            $url.=''.$connect->server->flag;
            $array[]=$url;
        }
        return $array;
    }
    public function online(Server $server){
        $response= $this->http->request(
            'post',
            $server,
            'panel/api/inbounds/onlines',
        );
        if ($response==null) {
            return [
                'sertverIp'=>$server->ip.' '.$server->name,
                'users'=>[],
                'count'=>0
            ];
        }

        return [
            'sertverIp'=>$server->ip.' '.$server->name,
            'users'=>$response->json('obj'),
            'count'=>count($response->json('obj') ?? [])
        ];
    }
    public function resetAllTraffics(Server $server)
    {
        return $this->http->request('post', $server,'panel/api/inbounds/resetAllTraffics');
    }
}
