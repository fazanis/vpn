<?php

namespace App\Services\Xui;

use App\Models\Server;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class HttpClient
{
    public function request(
        string $method,
        Server $server,
        string $url,
        mixed  $data = [],
    )
    {
        try {
            $http = Http::withoutVerifying();
            if ($server->token) {
                $http = $http->withToken($server->token);
            } else {
                $http = $http->asForm()
                    ->withOptions([
                        'cookies' => $this->login($server)
                    ])->withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'
                    ]);

            }

            return $http->{$method}(
                $server->type . '://' . $server->ip . ':' . $server->port . '/' . $server->folder . '/' . $url,
                $data
            );
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return null;
        }
    }
    public function requests(
        string $method,
        Collection $servers,
        string $url,
        array  $data = [],
    )
    {
        return Http::withoutVerifying()
            ->timeout(10)
            ->pool(function (Pool $pool) use ($method, $servers, $url, $data) {
                foreach ($servers as $server) {
                    $request = $pool->as($server->ip);
                    if ($server->token) {
                        $request->withToken($server->token);
                    } else {

                        if (!isset($this->cookies[$server->ip])) {
                            $this->cookies[$server->ip] = $this->login($server);
                        }

                        if (!$this->cookies[$server->ip]) {
                            continue; // пропускаем сервер
                        }

                        $request->withOptions([
                            'cookies' => $this->cookies[$server->ip],
                        ]);
                    }

                    $request->{$method}(
                        $this->makeUrl($server, $url),
                        $data
                    );
                }
            });
//                return $servers->map(function ($server) use ($pool, $method, $url, $data) {
//
//                    $url = $this->makeUrl($server, $url);
//                    if(!isset($this->cookies[$server->ip])){
//                        $this->cookies[$server->ip] = $this->login($server);
//                    }
//                    if (!$this->cookies[$server->ip]) {
//                        return null;
//                    }
//                    $pool= $pool
//                        ->as($server->ip);
//                    if ($server->token){
//                        $pool=$pool->withToken($server->token);
//                    }else{
//                        $pool= $pool ->withHeaders([
//                            'Content-Type' => 'application/json',
//                            'Accept' => 'application/json'
//                        ])
//                            ->withOptions([
//                                'cookies' =>$this->cookies[$server->ip]
//                            ]);
//                    }
//                    $pool= $pool->{$method}($url, $data);
//                    return $pool;
//                })->toArray();
//            }))->map(function ($response){
//            return $response instanceof \Illuminate\Http\Client\Response
//                ? $response
//                : null; // 💡 убираем Exception
//        });
    }

    public function login($server)
    {
        try {
            $this->cookies[$server->ip] = [];
            $response = Http::withoutVerifying()->post($this->makeUrl($server, 'login'), [
                'username' => $server->login,
                'password' => $server->password,
            ]);
            if ($response->successful()) {
                $this->cookies[$server->ip] = $response->cookies();
            }
            return $response->cookies();
        } catch (\Throwable $e) {
            return false;
        }
    }

    protected function makeUrl($server, string $uri): string
    {
        return "{$server->type}://{$server->ip}:{$server->port}/{$server->folder}/{$uri}";
    }
}
