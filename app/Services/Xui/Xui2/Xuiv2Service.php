<?php

namespace App\Services\Xui\Xui2;

use App\Models\Devise;
use App\Models\Server;
use App\Models\ServerInbound;
use App\Services\Xui\DTO\InboundsDTO;
use App\Services\Xui\DTO\ServerStatusDTO;
use App\Services\Xui\DTO\Stream\TcpSettingsDto;
use App\Services\Xui\HttpClient;
use App\Services\Xui\XuiBase;
use Illuminate\Support\Collection;

class Xuiv2Service extends XuiBase
{

    protected function deviseEmail($inboundId, $devise)
    {
        return $devise->ui_name .'_'.$inboundId;
    }
    public function createClient(Server $server,Devise $devise)
    {

        foreach ($this->getInbounds($server)->json('obj') as $inbound) {

            $clients = [];
                $clients[] = [
                    'id' => $devise->ui_id,
                    'flow' => "",
                    'email' => (string)$this->deviseEmail($inbound['id'], $devise),
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
                'id' => $inbound['id'],
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
        if ($r==null){
            return;
        }
        foreach($r->json('obj') as $client){
            $id=$client['id'];
            $client = json_decode($client['settings']);
              foreach (array_reverse($client->clients) as $item){
                  $r = $this->http->request('post', $server, "panel/api/inbounds/{$id}/delClientByEmail/{$item->email}");
              }

        }
    }
    public function subLink(Server $server,Devise $devise){
        $links=[];
        foreach ($server->load('inbounds')->inbounds as $inbound) {
            if ($inbound->sub_template){
                $links[] = str_replace('{uiid}', $devise->ui_id, $inbound->sub_template);
            }
        }

        return $links;
    }
    public function makeLink(Server $server,ServerInbound $connect,Devise $devise)
    {

//        $array = [];
//        foreach($server as $connect){
            $url='';
            $url.=$connect->protocol
                .'://'.'{uiid}'
                .'@'.$server->ip
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
            $url.='#'.$server->name;
            $url.=''.$server->flag;
//            $array[]=$url;
//        }
        return $url;
    }

    public function subTemplate(Server $server)
    {

        $devise = Devise::query()->first();
//            new Devise([
//            'ui_id' => 'ff64d556-f12e-4b9c-86b1-3244kjghkj1234',
//        ]);
        $inbounds = $this->getInbounds($server);
        foreach ($inbounds->json('obj') as $inbound){
            $r = InboundsDTO::make($inbound);
            $inbound=new ServerInbound(
                (array)$r
            );

           $result[] = $this->makeLink($server,$inbound,$devise);
//           $this->deleteClient($server,$devise);
        }
        return $result;
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
        $this->http->request('post', $server,'panel/api/inbounds/resetAllTraffics');
    }

    public function getTraffik(Server $server)
    {
        $response =  $this->http->request('get',$server,"panel/api/inbounds/list");
        $total=0;
        $result=[];
        if (!$response) {
            return [];
        }

        $obj = $response->json('obj') ?? [];
        $result = [];
        foreach ($obj as $inbound) {
            $inbound=json_decode($inbound['settings'])->clients;
            foreach($inbound as $client){
                $email = $client->id ?? '';
                $total= $client->totalGB; //(($client->up+$client->down) ?? 0);
                if (!isset($result[$email])) {
                    $result[$email] = 0;
                }
                $result[$email]+=$total;
            };
        }
        return $result;
    }
}
