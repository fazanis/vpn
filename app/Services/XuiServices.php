<?php

namespace App\Services;

use App\Models\Devise;
use App\Models\Server;
use App\Models\ServerInbound;
use App\Models\SincJobs;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class XuiServices
{
    protected $server;
    protected function request(string $method, string $url, array $data, $cookies=null)
    {
        // $option['cookies'] = $cookies;
        // $option['verify'] = false;
        
        $request = Http::withoutVerifying()->withOptions([
            'verify' => false,
        ]);
        if($cookies){
            $request->withCookies(
            $cookies,
            parse_url($this->serverUrl(), PHP_URL_HOST));
        }
       

        
        $response = $request->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->asForm()->$method($url, $data);
        if ($response->json()==null) {
            $this->login();
        }
    
        return $response;
    }

    protected function serverUrl(){
         return $this->server->type.'://'.$this->server->ip.':'.$this->server->port.'/'.$this->server->folder;
    }
    
    public function login()
    {
        try{
            $response =$this->request('post', $this->serverUrl().'/login/', [
            'username' => $this->server->login,
            'password' => $this->server->password,
            ]);
            return $response->cookies();
        }catch(Exception $e){

        }
        
    }
    protected function formatCookies($cookies): array
    {
        return collect($cookies)->mapWithKeys(function ($cookie) {
            return [$cookie->getName() => $cookie->getValue()];
        })->toArray();
    }
    protected function cookies()
    {
        $cookieJar = Cache::get('server_cookies_'.$this->server->id);

        if ($cookieJar) {
            $cookieJar = unserialize($cookieJar);
        } else {
            $cookieJar = $this->login($this->server);
        }
        return $cookieJar;
    }
    public function getImbount(Server $server,int $imbound)
    {
        $this->server=$server;
        $cookie=$this->login();
        $cookie = $this->formatCookies($cookie);
        return $this->request('get',  $this->serverUrl().'/panel/api/inbounds/get/'.$imbound, [],  $cookie)->json();
    }
    public function getImbounts(Server $server)
    {
        $this->server=$server;
        $cookie=$this->login();
        $cookie = $this->formatCookies($cookie);
        return $this->request('get',  $this->serverUrl().'/panel/api/inbounds/list', [],  $cookie)->json();
    }
    public function status(Server $server)
    {
        $this->server=$server;
        return $this->request('get',  $this->serverUrl().'/panel/api/server/status/', [], $this->cookies())->json();
    }
    public function addClient(ServerInbound $server,Devise $devise,int $day=0)
    {
        try{
      
        $this->server=$server->server;
        $imbount = $server->inbound;
 
        $cookie=$this->login();
        $cookie = $this->formatCookies($cookie);
        $data = [
            'id' => $imbount,
            'settings' => json_encode([
                'clients' => [[
                    "id"=> $devise->ui_name,
                    "flow"=> "",
                    "email"=> (string) $devise->ui_name.'_'.$server->inbound,
                    "limitIp"=> 0,
                    "totalGB"=> 0,
                    "expiryTime"=>0, //$day==0 ? 0 : Carbon::now()->addDay($day)->timestamp,
                    "enable"=> true,
                    "tgId"=> "",
                    "subId"=> str()->uuid()->toString(),
                    "comment"=> "",
                    "reset"=> 0
                ]]
            ])
        ];
        return $this->request('post', $this->serverUrl().'/panel/api/inbounds/addClient',$data, $cookie)->json();
      }catch(Exception $e){
            dd($e->getMessage());
        }
    }
    

    public function getConnect(Server $server, User $user)
    {
        $this->server=$server;
      
        $cookie=$this->login();
        
        $cookie = $this->formatCookies($cookie);
        // dd('vless://'.$user->ui_id.'@'.str_replace($this->server->url,'https://',''));
        try{
        $response = $this->request(
            'get',
            $this->serverUrl().'/panel/api/inbounds/get/'.$this->server->imbound,
            [],
            $cookie
        )->json();
        $streamSettings= json_decode($response['obj']['streamSettings']);
        $settings= json_decode($response['obj']['settings']);
        $key='';
        
        $key= $response['obj']['protocol'].'://'.$user->ui_id.'@'.$server->ip.':'.$response['obj']['port'].'?type='.
        $streamSettings->network.'&encryption='.$settings->encryption;
        
        if(!$streamSettings->network==='tcp'){
            $key.='&path='.$streamSettings?->xhttpSettings?->path.
            '&host='.$streamSettings?->xhttpSettings?->host.
            '&mode='.$streamSettings?->xhttpSettings?->mode;
        }
        $key.='&security='.$streamSettings?->security.
        '&pbk='.$streamSettings?->realitySettings?->settings->publicKey.
        '&fp='.$streamSettings?->realitySettings?->settings?->fingerprint.
        '&sni='.$streamSettings?->realitySettings?->serverNames[0].
        '&sid='.$streamSettings?->realitySettings?->shortIds[0].
        '&spx='.$streamSettings?->realitySettings?->settings?->spiderX.
        '&pqv='.$streamSettings?->realitySettings?->settings?->mldsa65Verify;
    
        return $key;
        }catch(Exception $e){
            dd($e->getMessage());
        }
        
        
    }

    public function clientTraffikById(Server $server,User $user)
    {
        $this->server=$server->server;
        $cookie=$this->login();
        $cookie = $this->formatCookies($cookie);

        return $this->request(
            'get', 
            $this->serverUrl().'/panel/api/inbounds/getClientTrafficsById/'.$user->ui_id,
            [], 
            $cookie
        )->json();
        
    }
    public function clientExists(Server $server,User $user): bool
    {
   
    try {
        $this->server=$server;
        
        $cookie=$this->login();
        $cookie = $this->formatCookies($cookie);
        $response = $this->request(
            'get', 
            $this->serverUrl().'/panel/api/inbounds/get/'. $this->server->imbound,
            [], 
            $cookie
        );

        if (!$response->successful()) {
            return false;
        }

        $data = $response->json();

        if (!isset($data['obj']['settings'])) {
            return false;
        }

        $settings = json_decode($data['obj']['settings'], true);

        if (!isset($settings['clients'])) {
            return false;
        }

        foreach ($settings['clients'] as $client) {
            if (isset($client['id']) && $client['id'] === $user->ui_id) {
                return true;
            }
        }

        return false;

    } catch (\Exception $e) {
        \Log::error('clientExists error: ' . $e->getMessage());
        return false;
    }
    }
    public function deleteClient(ServerInbound $server,Devise $devise)
    {
        $this->server=$server->server;
        $imbount = $server->inbound;
        $cookie=$this->login();
        $cookie = $this->formatCookies($cookie);
        return $this->request(
            'post', 
            $this->serverUrl().'/panel/api/inbounds/'.$imbount.'/delClient/'.$devise->ui_name,
            [], 
            $cookie
        )->json();
    }

    public function serverStatus(Server $server)
    {
        try{
        $this->server=$server;
        $cookie=$this->login();
        $cookie = $this->formatCookies($cookie);
        return $this->request(
            'get', 
            $this->serverUrl().'/panel/api/server/status',
            [], 
            $cookie
        )->json();
       }catch(Exception $e){

       }
    }
    public function onlineUsers(Server $server)
    {
        try{
            $this->server=$server;
            $cookie=$this->login();
            $cookie = $this->formatCookies($cookie);
            return $this->request(
                'post', 
                $this->serverUrl().'/panel/api/inbounds/onlines',
                [], 
                $cookie
            )->json();
        }catch(Exception $e){}
        
       
    }
}
