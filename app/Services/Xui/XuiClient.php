<?php

namespace App\Services\Xui;

use Illuminate\Http\Client\Pool;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class XuiClient
{
    protected array $cookies = [];

    public function request(string $method,$server, string $uri, array $data = [])
    {
        try {


        if(!isset($this->cookies[$server->ip])){
            $this->cookies[$server->ip] = $this->login($server);
        }

        $url = $this->makeUrl($server, $uri);

        $response = Http::withoutVerifying()
            ->asForm()
            ->withOptions([
                'cookies' =>$this->cookies[$server->ip]
            ])
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])
            ->{$method}($url, $data);
        return $response;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return null;
        }
    }
    public function poolRequest(string $method, Collection $servers, string $uri, array $data = [])
    {
            return collect(Http::withoutVerifying()
                ->timeout(10)
                ->retry(2, 500)
                ->pool(function (Pool $pool) use ($method, $servers, $uri, $data) {
                    return $servers->map(function ($server) use ($pool, $method, $uri, $data) {

                        $url = $this->makeUrl($server, $uri);
                        if(!isset($this->cookies[$server->ip])){
                            $this->cookies[$server->ip] = $this->login($server);
                        }

                        return $pool
                            ->as($server->ip)
                            ->withHeaders([
                                'Content-Type' => 'application/json',
                                'Accept' => 'application/json'
                            ])
                            ->withOptions([
                                'cookies' =>$this->cookies[$server->ip]
                            ])
                            ->{$method}($url, $data);

                    })->toArray();
                }))->map(function ($response){
                return $response instanceof \Illuminate\Http\Client\Response
                    ? $response
                    : null; // 💡 убираем Exception
            });



    }


    /**
     * Проверяем, залогинены ли
     */
    protected function ensureAuthenticated(Collection $servers)
    {
        $needLogin = $servers->filter(function ($server) {
            return !isset($this->cookies[$server->ip]);
        });

        if ($needLogin->isNotEmpty()) {
            $this->login($needLogin);
        }
    }

    /**
     * Логин через pool
     */
    public function login($server)
    {
        $response = Http::withoutVerifying()->post($this->makeUrl($server, 'login'), [
            'username' => $server->login,
            'password' => $server->password,
        ]);
        if ($response->successful()) {
            $this->cookies[$server->ip] = $response->cookies();
        }
        return $response->cookies();
//        $responses = Http::withoutVerifying()
//            ->pool(function (Pool $pool) use ($servers) {
//
//                return $servers->map(function ($server) use ($pool) {
//
//                    return $pool
//                        ->as($server->ip)
//                        ->post($this->makeUrl($server, 'login/'), [
//                            'username' => $server->login,
//                            'password' => $server->password,
//                        ]);
//
//                })->toArray();
//            });
//
//        foreach ($responses as $ip => $response) {
//            if ($response->successful()) {
//                $this->cookies[$ip] = $response->cookies();
//            }
//        }
    }

    protected function makeUrl($server, string $uri): string
    {
        return "{$server->type}://{$server->ip}:{$server->port}/{$server->folder}/{$uri}";
    }
    protected function formatCookies($cookies): array
    {
        $result = [];

        foreach ($cookies as $cookie) {
            $result[$cookie->getName()] = $cookie->getValue();
        }

        return $result;
    }

}
