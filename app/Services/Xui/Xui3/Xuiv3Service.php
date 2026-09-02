<?php

namespace App\Services\Xui\Xui3;

use App\Models\Devise;
use App\Models\Server;
use App\Models\ServerInbound;
use App\Services\Xui\DTO\ServerStatusDTO;
use App\Services\Xui\HttpClient;
use App\Services\Xui\Services\BaseService;
use App\Services\Xui\XuiClient;
use App\Services\Xui\XuiBase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Psy\Util\Str;

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
                "auth"=>$devise->ui_id,
                "id"=>$devise->ui_id,
            ],
            "inboundIds" => collect($this->getInbounds($server)->json('obj'))->pluck('id')->toArray()
            //$server->inbounds->pluck('inbound')->map(fn($id) => (int)$id)->values()->toArray()
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

    public function getTraffik(Server $server)
    {
        $response = $this->http->request('get',$server,"panel/api/clients/list");
        if (!$response) {
            return [];
        }

        $obj = $response->json('obj') ?? [];
        $result = [];
        foreach ($obj as $inbound) {
            $email = $inbound['uuid'] ?? '';
            $total = ($inbound['traffic']['up'] ?? 0) + ($inbound['traffic']['down'] ?? 0);
            if (!isset($result[$email])) {
                $result[$email] = 0;
            }

            $result[$email]+=$total;
        }
        return $result;
    }

    public function subLink(Server $server,Devise $devise)
    {
        $links=[];
        foreach ($server->load('inbounds')->inbounds as $inbound) {
            if ($inbound->sub_template){
                $links[] = str_replace('{uiid}', $devise->ui_id, $inbound->sub_template);
            }
        }

        return $links;
    }
    public function subTemplate(Server $server)
    {

        $devise = new Devise([
            'name'=>'settings',
            'user_id'=>0,
            'ui_id'=>str()->uuid()->toString(),
            'ui_name'=>str()->slug('settings').''. substr(\Illuminate\Support\Str::uuid(),0,10),
        ]);

        $this->createClient($server,$devise);

        $response = $this->http->request('get',$server,"panel/api/clients/subLinks/{$devise->ui_id}");

        if ($response === null) {
            return null;
        }
        foreach ($response->json('obj') as $item){
            $pos = strpos($item, '#');
            if ($pos !== false) {
                $item = substr($item, 0, $pos);
            }

            $template = str_replace($devise->ui_id,'{uiid}',$item);
            $url_arry=(parse_url($template));
//            parse_str($url_arry['query'] ?? '', $query);
//            dd($template,$query);
            $result[] = $template.'#'.$server->flag.$server->name;
        }
        $this->deleteClient($server,$devise);
//        $devise->delete();
        return $result;
//        $array = [];
//
//        foreach($server->inbounds as $connect){
//            $url='';
//            $url.=$connect->protocol
//                .'://'.$devise->ui_id
//                .'@'.$connect->server->ip
//                .':'.$connect->port
//                .'?type='.$connect->type
//                .'&encryption='.$connect->encryption;
//            if ($connect->type==='xhttp'){
//                $url.='&path='.$connect->path;
//                $url.='&host='.$connect->host;
//                $url.='&mode='.$connect->mode;
//            }
//            $url.='&security='.$connect->security;
//             $url.='&pbk='.$connect->pbk;
//             $url.='&fp='.$connect->fp;
//             $url.='&sni='.$connect->sni;
//             $url.='&sid='.$connect->sid;
//             $url.='&spx='.$connect->spx;
//             $url.='&pqv='.$connect->pqv;
//             $url.='#'.$connect->server->name;
//             $url.=''.$connect->server->flag;
//            $array[]=$url;
//        }
//        return $array;
    }
    public function online(Server $server){
        $response= $this->http->request(
            'post',
            $server,
            'panel/api/clients/onlines',
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
        $this->http->request('post', $server,'panel/api/clients/resetAllTraffics');
    }
    public function createInbound(Server $server)
    {
        $response = $this->http->request('get', $server, 'panel/api/server/getNewX25519Cert');
        $getNewmldsa65 = $this->http->request('get', $server, 'panel/api/server/getNewmldsa65');
//        dd($getNewmldsa65->json('obj'));
        $payload = [
            'remark' => 'test',
            'enable' => true,
            'listen' => '',
            'port' => 2222,
            'protocol' => 'vless',
            'expiryTime' => 0,
            'total' => 0,

            'settings' => json_encode([
                'clients' => [],
                'decryption' => 'none',
                'fallbacks' => [],
            ], JSON_UNESCAPED_SLASHES),

            'streamSettings' => json_encode([
                'network' => 'tcp',
                'security' => 'reality',

                'tcpSettings' => [
                    'acceptProxyProtocol' => false,
                    'header' => [
                        'type' => 'none',
                    ],
                ],

                'realitySettings' => [
                    'show' => false,
                    'xver' => 0,
                    'target'=>"google.com:443",
                    'dest' => "google.com:443",                // google.com:443
                    'serverNames' => [
                        "google.com",                // google.com
                    ],
                    'privateKey' => $response->json('obj.privateKey'),
                    'minClient' => '',
                    'maxClient' => '',
                    'maxTimediff' => 0,
                    'shortIds' => [
                        'asdadasdasd',
                    ],
                    "mldsa65Seed"=> $getNewmldsa65->json('obj.seed'),
                    'settings' => [
                        'publicKey' =>  $response->json('obj.publicKey'),
                        'fingerprint' => "firefox", // chrome
                        'serverName' => "google.com",
                        'spiderX' => '/',
                        'mldsa65Verify'=>$getNewmldsa65->json('obj.verify')
                    ],
                ],
            ], JSON_UNESCAPED_SLASHES),

            'sniffing' => json_encode([
                'enabled' => true,
                'destOverride' => [
                    'http',
                    'tls',
                    'quic',
                    'fakedns',
                ],
                'metadataOnly' => false,
                'routeOnly' => false,
            ], JSON_UNESCAPED_SLASHES),

            'allocate' => json_encode([
                'strategy' => 'always',
                'refresh' => 5,
                'concurrency' => 3,
            ], JSON_UNESCAPED_SLASHES),
        ];

//        $array = [
//            "enable" => true,
//            "remark" => "VLESS-8443",
//            "listen" => "",
//            "port" => 8443,
//            "protocol" => "vless",
//            "expiryTime" => 0,
//            "total" => 0,
//            "settings"=>[
//                "publicKey"=> $response->json('obj.publicKey'),
//                "clients"=>[],
//                "decryption"=>"none",
//                "fallbacks"=> []
//            ],
//            "streamSettings"=>[
//                "network"=> "tcp",
//                "security"=>"reality",
//                "realitySettings"=> [
//                    "privateKey"=>$response->json('obj.privateKey'),
//                    "show"=> false,
//                    "dest"=> "..."
//                ],
//            ],
//
//            "sniffing"=>[
//                "enabled"=> true,
//                "destOverride"=> [
//                    "http",
//                    "tls"
//                ]
//            ]
//        ];
        $response = $this->http->request('post', $server, 'panel/api/inbounds/add', $payload);
        dd($response->json());
    }

    public function getSubLinksFromImbaund($serverInbound)
    {
        dd($serverInbound);
    }
    private function parseSubTeplate(string $rawLink): string
    {
        $parts = parse_url($rawLink);

        $uuid = $parts['user'] ?? null;   // то, что между // и @
        $tag  = isset($parts['fragment']) ? rawurldecode($parts['fragment']) : null;

        $template = $rawLink;

        // Заменяем именно найденную строку, а не по regex —
        // parse_url уже сказал нам точное значение, так что str_replace безопасен
        if ($uuid !== null) {
            $template = str_replace('//' . $uuid . '@', '//{uuid}@', $template);
        }

        if (isset($parts['fragment'])) {
            $template = str_replace('#' . $parts['fragment'], '#{tag}', $template);
        }

        return $template;
    }

    public function allLink($server)
    {
        $response = $this->http->request('get', $server, 'panel/api/inbounds/allLinks');
        dd($response->json());
    }
}
