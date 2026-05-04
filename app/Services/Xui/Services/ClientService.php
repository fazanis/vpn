<?php

namespace App\Services\Xui\Services;

use App\Models\Devise;
use App\Models\Server;
use Illuminate\Support\Collection;
use function Pest\Laravel\json;

class ClientService extends BaseService
{
    public function createClients(Collection $servers, Collection $devises)
    {
        foreach ($servers as $server) {
            foreach ($server->inbounds as $inbound) {
                $clients = [];
                foreach ($devises as $devise) {
                    $clients[] = [
                        'id' => $devise->ui_id,
                        'flow' => "",
                        'email' => (string)$devise->ui_name . '_' . $inbound->inbound,
                        'limitIp' => 0,
                        'totalGB' => 0,
                        'expiryTime' => 0,
                        'enable' => true,
                        'tgId' => "",
                        'subId' => str()->uuid()->toString(),
                        'comment' => "",
                        'reset' => 0,
                    ];
                }
                $data = [
                    'id' => $inbound->inbound,
                    'settings' => json_encode([
                        'clients' => $clients
                    ])
                ];

                try {
                    $response = $this->request('post', $server, 'panel/api/inbounds/addClient', $data);
                    dump($response->status());
                } catch (\Exception $exception) {
                    dump($exception->getMessage());
                }

            }
        }
    }

    public function createClient(Collection $servers, Devise $devise)
    {
//        dd($devise);
        foreach ($servers as $server) {
            foreach ($server->inbounds as $inbound) {
                $clients = [
                    'id' => (string)$devise->ui_id,
                    'flow' => "",
                    'email' => (string)$devise->ui_name . '_' . $inbound->inbound,
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
                        'clients' => [$clients]
                    ])
                ];
                try {
                    $response = $this->request('post', $server, 'panel/api/inbounds/addClient', $data);
                } catch (\Exception $exception) {
                    dump($exception->getMessage());
                }

            }
        }

    }

    public function delete($servers, $devise)
    {
        foreach ($servers as $server) {
            foreach ($server->inbounds as $inbound) {
                $devise_id = $devise->ui_name . '_' . $inbound->inbound;
                $this->request('post', $server, "panel/api/inbounds/{$inbound->inbound}/delClientByEmail/{$devise_id}");
            }
        }
    }

    public function updateClients($servers, $devises)
    {
        $servers = $servers instanceof Collection ? $servers : collect([$servers]);
            foreach ($servers as $server) {
                try {
                foreach ($server->inbounds as $inbound) {
                    $clients = [];
                    foreach ($devises as $devise) {
                        $clients[] = [
                            'id' => (string)$devise->ui_id,
                            'flow' => "",
                            'email' => (string)$devise->ui_name . '_' . $inbound->inbound,
                            'limitIp' => 0,
                            'totalGB' => 0,
                            'expiryTime' => 0,
                            'enable' => true,
                            'tgId' => "",
                            'subId' => str()->uuid()->toString(),
                            'comment' => "",
                            'reset' => 0,
                        ];
                    }

                    $oldInbound = $this->request('get', $server, 'panel/api/inbounds/get/' . $inbound->inbound);
//                    dd($oldInbound->json('obj'));
                    $oldInbound = $oldInbound->json('obj');

                    $settings = json_decode($oldInbound['settings'] ?? null, true);
                    $settings['clients'] = $clients;
                    $oldInbound['settings'] = json_encode($settings, JSON_UNESCAPED_UNICODE);

                    $response = $this->request('post', $server, 'panel/api/inbounds/update/' . $inbound->id, $oldInbound);
                }
                } catch (\Exception $exception) {
                    dump($exception->getMessage());
                }
            }

//        foreach ($servers as $server) {
//        $r = $this->request('get', $server, 'panel/api/inbounds/list');
//            foreach($r->json('obj') as $inbound){
//                foreach(json_decode($inbound['settings'])->clients as $client){
//                    if(!Devise::query()->where('ui_id',$client->subId)->exists()){
//                        $r = $this->request('post', $server, "panel/api/inbounds/{$inbound['id']}/delClient/{$client->id}");
//                        dump($r->json(),$client->subId);
//                    }
//                }
//            }
//        }
    }

    public function online($servers)
    {
        $total = 0;
        foreach ($servers as $server) {
            $total += count($this->request('post', $server, 'panel/api/inbounds/onlines')->json()['obj']);
        }
        return $total;
    }

    public function onlineCount(Collection $servers)
    {
        $responses = $this->online($servers);

        $total = 0;
        $perServer = [];

        foreach ($responses as $ip => $response) {

            if (!$response->successful()) {
                $perServer[$ip] = 0;
                continue;
            }

            $count = count($response->json()['obj'] ?? []);

            $perServer[$ip] = $count;
            $total += $count;
        }

        return [
            'total' => $total,
            'servers' => $perServer,
        ];
    }

    public function sincClient($servers, $devises)
    {
        $servers = $servers instanceof Collection ? $servers : collect([$servers]);
        foreach ($servers as $server) {
            foreach ($server->inbounds as $inbound) {
                foreach ($devises as $devise) {
                    $clients = [
                        'id' => (string)$devise->ui_id,//(string)$devise->ui_id,
                        'flow' => "",
                        'email' => (string)$devise->ui_name . '_' . $inbound->inbound,
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
                            'clients' => [$clients]
                        ])
                    ];
                    try {
                        $response = $this->request('post', $server, 'panel/api/inbounds/addClient', $data);
                    } catch (\Exception $exception) {
                        dump($exception->getMessage());
                    }

                }
            }
        }

    }

    public function updateClient($servers, $devises)
    {
        $servers = $servers instanceof Collection ? $servers : collect([$servers]);
        $devises = $devises instanceof Collection ? $devises : collect([$devises]);

        foreach ($servers as $server) {
            foreach ($server->inbounds as $inbound) {
                foreach ($devises as $devise) {
                    $clients = [
                        'id' => (string)$devise->ui_id,
                        'flow' => "",
                        'email' => (string)$devise->ui_name . '_' . $inbound->inbound,
                        'limitIp' => 0,
                        'totalGB' => 0,
                        'expiryTime' => 0,
                        'enable' => true,
                        'tgId' => "",
                        'subId' => $devise->ui_id,
                        'comment' => "",
                        'reset' => 0,
                    ];
                    $data = [
                        'id' => $inbound->inbound,
                        'settings' => json_encode([
                            'clients' => [$clients]
                        ])
                    ];
                    try {
                        $uiid = $devise->ui_name . '_' . $inbound->inbound;
                        $response = $this->request('post', $server, "panel/api/inbounds/updateClient/{$uiid}", $data);
                    } catch (\Exception $exception) {
                        dump($exception->getMessage());
                    }

                }
            }
        }
    }

    public function getTrafik(Collection $servers, Devise $devise)
    {
        $responses= $this->requests('get',$servers,'panel/api/inbounds/getClientTrafficsById/'.$devise->ui_id);
        $total=0;
        foreach($responses as $response){
            $total+=array_sum(array_column($response->json('obj'), 'allTime'));
        }
        return $total;
    }
}
