<?php

namespace App\Services\Xui\Xui3;

use App\Models\Devise;
use App\Models\Server;
use App\Services\Xui\DTO\ServerStatusDTO;
use App\Services\Xui\HttpClient;
use App\Services\Xui\Services\BaseService;
use App\Services\Xui\XuiClient;
use App\Services\Xui\XuiBase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class Xuiv3Service extends XuiBase
{
    protected function deviseEmail($devise)
    {
        return $devise->ui_name;
    }

    public function createClient(Server $server, Devise $devise)
    {

        $data = [
            "client" => [
                "email" => $this->deviseEmail($devise),
                "totalGB" => 0,
                "expiryTime" => 0,
                "tgId" => 0,
                "limitIp" => 0,
                "enable" => true,
                "subId"=>$devise->ui_id,
                "id"=>$devise->ui_id,
            ],
            "inboundIds" => $server->inbounds->pluck('inbound')->map(fn($id) => (int)$id)
                ->values()->toArray()
        ];

        $this->http->request('post', $server, 'panel/api/clients/add', $data);

    }

    public function deleteClient(Server $server, Devise $devise)
    {
        $this->http->request('post', $server, "panel/api/clients/del/" . $this->deviseEmail($devise));
    }

    public function delAllClients(Server $server)
    {
        foreach ($server->inbounds as $inbound) {
            $this->http->request('post', $server, "panel/api/inbounds/{$inbound->inbound}/delAllClients");
        };

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

//    public function subLink(Server $server,Devise $devise)
//    {
////        return $this->http->request('get', $server,"panel/api/inbounds/list/slim")->json('obj');
////        return $this->http->request('get', $server,"panel/api/clients/get/{$this->deviseEmail($devise)}")->json('obj');
//        return $this->http->request('get', $server,"panel/api/clients/subLinks/{$devise->ui_id}")->json('obj');
////        return $this->http->request('get', $server,"panel/api/clients/get/{$this->deviseEmail($devise)}")->json();
//    }
    public function createInbound(Server $server)
    {
        $array = [
            "enable" => true,
            "remark" => "VLESS-8443",
            "listen" => "",
            "port" => 8443,
            "protocol" => "vless",
            "expiryTime" => 0,
            "total" => 0,
            "settings"=>[
                "clients"=>[],
                "decryption"=>"none",
                "fallbacks"=> []
            ],
            "streamSettings"=>[
                "network"=> "tcp",
                "security"=>"reality",
                "realitySettings"=> [
                    "show"=> false,
                    "dest"=> "..."
                ],
            ],

            "sniffing"=>[
                "enabled"=> true,
                "destOverride"=> [
                    "http",
                    "tls"
                ]
            ]
        ];
        $response = $this->http->request('post', $server, 'panel/api/inbounds/add', $array);
        dd($response->json());
    }
}
