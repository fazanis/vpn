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

            $result[$email]=$total;
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
        $setting='{
              "listen": "",
              "port": 46512,
              "protocol": "vless",
              "tag": "in-46512-tcp",
              "settings": {
                "clients": [],
                "decryption": "none",
                "encryption": "none"
              },
              "sniffing": {
                "enabled": false
              },
              "streamSettings": {
                "network": "xhttp",
                "xhttpSettings": {
                  "path": "/",
                  "host": "",
                  "mode": "auto",
                  "xPaddingBytes": "100-1000",
                  "xPaddingObfsMode": false,
                  "xPaddingKey": "",
                  "xPaddingHeader": "",
                  "xPaddingPlacement": "",
                  "xPaddingMethod": "",
                  "sessionIDPlacement": "",
                  "sessionIDKey": "",
                  "sessionIDTable": "",
                  "sessionIDLength": "",
                  "seqPlacement": "",
                  "seqKey": "",
                  "uplinkDataPlacement": "",
                  "uplinkDataKey": "",
                  "scMaxEachPostBytes": "",
                  "noSSEHeader": false,
                  "scMaxBufferedPosts": 30,
                  "scStreamUpServerSecs": "20-80",
                  "serverMaxHeaderBytes": 0,
                  "uplinkHTTPMethod": "",
                  "headers": {},
                  "scMinPostsIntervalMs": "",
                  "uplinkChunkSize": 0,
                  "noGRPCHeader": false,
                  "enableXmux": false
                },
                "security": "reality",
                "realitySettings": {
                  "show": false,
                  "xver": 0,
                  "target": "www.amd.com:443",
                  "serverNames": [
                    "amd.com",
                    "www.radeon.com",
                    "www.gpuopen.com",
                    "www.amd.com",
                    "verification.amd.com",
                    "support.amd.com",
                    "support-sit.amd.com",
                    "shop.amd.com",
                    "shop-us-en.amd.com",
                    "shop-eu-fr.amd.com",
                    "shop-eu-en.amd.com",
                    "shop-eu-de.amd.com",
                    "shop-ca-en.amd.com",
                    "search.amd.com",
                    "radeon.com",
                    "products.amd.com",
                    "pro.radeon.com",
                    "mars.amd.com",
                    "instinct.radeon.com",
                    "gpuopen.com",
                    "gaming.radeon.com",
                    "explore.amd.com",
                    "drivers.amd.com",
                    "download.amd.com",
                    "creators.radeon.com",
                    "connect.amd.com",
                    "connect-sit.amd.com",
                    "account.amd.com"
                  ],
                  "privateKey": "yJzabCjHCoGIYxd963ZZEq-tGAtDSRzCzYoz0wAqt2Q",
                  "minClientVer": "",
                  "maxClientVer": "",
                  "maxTimediff": 0,
                  "shortIds": [
                    "94c8",
                    "e9f1568aed",
                    "ac",
                    "b0acd2faa20c",
                    "fea05e",
                    "3d495590",
                    "e3bdb8ba6ef847fd",
                    "4d92c27fadb99f"
                  ],
                  "mldsa65Seed": "k6jSCGbiswSMlBk1cIUFT3CAkQuH9KPL2wT3b49moG0",
                  "settings": {
                    "publicKey": "7X8__eViVqeZQVNg0aq4XFR8Xtd5TnUNjV2Ycl-T1gA",
                    "fingerprint": "firefox",
                    "serverName": "",
                    "spiderX": "/OqEVVKqm86z8wxO",
                    "mldsa65Verify": "bI4vtWIBQZbaiauIvuzCY-ju1h3RpgfF5nvsr8A30Ile3yXob95LawG6-uIW3E2w_ioMRdpeJUc9HFF0lQ0MN5sJVT40joWejL1xZ9RZqJKn_R7fRShab_ifZTK6hN0wnDiINYYyYXfVKzdXOfUuXmMALufkj055DlfUV2Al85CJi87kLKDvcaELZYFO8oUOFPD77nl2okSkweb566_HsgHKC6OeDDU09OckZduc6CGmmmbCG4MnzScjxYPOzgyUOieskhNJquRFGq3jMWaza1WhxAUX8nLvw84D6U-dy_Cayvft1UrBQNVRsN6JYRzHQGMPOPDMfWp9dKjp_GzOtRNcXqUi7YIcpIDwLWwE-BfMcKx_cpZv2_snkH9Wk9rMCyQvs44W2V9GZApxpRH479_f9HyiJe-RSw5RVzXJv6nBtRpoMJg-6iHhrrh-YPSAuU_tXsXjyfSGLuHEZhTSGc0bMxdwQeZIktM789a_gb42Sd-yvmiEmJI11uE_fZ5g3q_YCn3H4qvIv09xc513mvS0qQdl07-1eTgsAzO-rrdu79pVuzTs5_DxGJ70AC1F-0q8SjIyVVU8cxswZtcvEhrzi-zV6ru055E2wsANOhenWw8QWp54mmc9seG0BgDaClLkRkCBCv3Y1Ey8cpxwYyEnODGU0Xipqp0QqHo0WDJ-p2rgdx-7ns6oKJ6V1lEGAW-WtfoE32IrMZ8-a0g3MCOK3PkGnKFYGOJbxrWE0UHS0kK2Gjhesb_CMDdOrfe8_6EmTpFkjwcfMXj9YEFs2Ij_dDDEbg36eqrGQYuB-J2h1CfKhQzFNCcuIjTJFD7PaTYQEHUuGbsUADc5SzA_JxTavT-IKY8vxwlSwpNYaKE7n8JJR2o8EKoMVS43I8YRZIu_inZoslRDOQzHnOqXNAuDVQegaNVOWxtYFKBedwhUopIWfmeWnW7Yfclb2Kly2gqCew9X6rK69TkpYSUcQZvapnkPzYZ9f16XcqpC6ntvM4y2yPYdTG57yk7irqbOe3qoM7MafW0UdSv1W2ZrcSEqXeE7rQgFe3DLKSCmvGDX0MlIQbAG1aoc5bJcTjxQeg_eH6ND7sD7GKqQFzki_W_m810qBV67-B9UJkXPjg3yDuS_-gPQsnahRWs_6scQmBRrhj7XnVHptCoCelGCOd4YNf3NOXJNa6PwUoIUkoqPa_TyqvCMh_vy6Ys3ThYWMLAtWpfrs8nfwJtWJfdFaqTPsyPri4TzfhL4VhlCrR3uHqHGt7ph6uaFnhGstke_Hc7yHbr9TwZnX_Tm421D1xKpF9W2ke1vbKfOFNezJB4-qaf5eiS_Xqe6yN-YrMb9MbENk50NlUhba4n2ZgVSyZI7lcmsNrclr6HDgiRVQVIqJzQeXUGHMvmKw3DL9cybtfnH3bTA3UzIneqUZeVhFboCYmG-6r-TkNggRAIpuH0vI9hHd4QG5EPzd4qAaxO1Zvkd0qx8a9A1PRAnBXN5viEHFAk-aBTf8G7KJmwyu0OVKfPaNkkGPxh7ofx8DNYbHIUP7Yyuhl7NyJdW0i8qc3AlMgZ0-tl8uwV9r7O8VZDI5tfT5n-QZvIkuHw6MlfNIeq_2RoIZDPVgoG7itRvOutHcQlE44_C5TAzjnee5iFyAA7fxA_CZddmLO0e0dtQ0-TFYjbt7vsEs_gl3g01g0a61FokqDxy5c2Pl56IvsB32B-tBxmiXzRYc6B3LH1OS_WZcpSL6Kr8mS4u5wpXPQDmL14a9YCdNsnrciSfh6upB06RUj_NeRNGf8j8WGGHsm72E4Aq6zYHAK1zOtVNx9ScCsEdxwoeQZ-antf_UEY6CgL1zBb10T-WDDUu0GXJLK1LRWNyAPVGdTMzuic9UyAN4SP7avJ9eYl4KmV-ew2-FCI4knSisNuYkMmfzZr-0nSFKHifbifCayAHAXSNs2V3JOLrz7GpEfj2SWUCZ_o_Qs6Old4USM83prtdVNvlMQAyD7uSi8Rs-oB03_NsodF8Nr9oO9KCaSZg803mNy8UwLVXBvepdCYP8tRBpx7coJKqm8xYc0fv8WNB9D99JOKTs9pCg33V_E5UbZKdw91kUoqVMPFGXfbGS8uPI9QHS0o2pvmtM6Pm5X5fOjU8YOzT8gXsP6VdKR0PPpmnkLOQIi3wcM_eRiSYcypJNbTqnP4rSK2_SO_re2tJ1bHYY5qBuYKaL0pTP389a2GJHQ1ZSDHIGC_qTdi4c1tsz4F_5jMjh71pa2r-MGTR3IjZTFjNH6_6dUQwmEgntCt3xUkF_xd7UK3ybAWqSRz-IAJ5WKXZZOLcpa1c_-fYhF_S5EDq-hcODHj_tDce1wHJzEO0tf1fHRdXRpgWZTD_-U1rXatGpNe8ghyIJ9f2hKHNYgzW3Aj_cfqEkBeUBooaFiwzeL_clXISY2IdK5jPEzVAI1OwufpFezWy-NaE_iX7g1mMIphMdKBygrRcJyej2R_ddfZ_RAjzui06YpO1-6WzdhxkJQ5L5_Hhpxa2bPa8aCYSB_zDtN5ckixu87dQtcqDvAYqyz2A3w13uRNeChO7BF8a0VqzQ444jmhIL2UZtFrfA6wbyxjzGfWUMMK3qhM"
                  }
                }
              }
            }';

//        $response = $this->http->request('get',$server,'panel/api/server/getNewX25519Cert');
        $response = $this->http->request('get',$server,'panel/api/server/getNewmldsa65');
        dd($response->json('obj'));
        $response = $this->http->request('post',$server,'panel/api/inbounds/add',json_decode($setting));
        dd($response,$response->json());
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
