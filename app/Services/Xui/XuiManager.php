<?php

namespace App\Services\Xui;

use App\Models\Server;
use App\Services\Xui\Xui2\Xuiv2Service;
use App\Services\Xui\Xui3\Xuiv3Service;
use Illuminate\Support\Collection;

class XuiManager
{
    public function __construct(
        protected Xuiv2Service $v2,
        protected Xuiv3Service $v3
    ) {
    }

    protected function driver(Server $server)
    {
        return $server->token
            ? $this->v3
            : $this->v2;
    }

    protected function groups(Collection $servers)
    {
        return $servers->groupBy(function (Server $server) {
            return $server->token
                ? 'v3'
                : 'v2';
        });
    }

    public function __call($method, $arguments)
    {
        $target = $arguments[0];

        // один сервер
        if ($target instanceof Server) {

            return $this
                ->driver($target)
                ->{$method}(...$arguments);
        }

        // коллекция
        if ($target instanceof Collection) {

            $result = collect();

            foreach ($this->groups($target) as $driver => $servers) {

                $service = match ($driver) {
                    'v2' => $this->v2,
                    'v3' => $this->v3,
                };

                $response = $service->{$method}($servers);

                if ($response instanceof Collection) {
                    $result = $result->merge($response);
                } else {
                    $result->push($response);
                }
            }

            return $result;
        }

        throw new \Exception('Unknown argument.');
    }
}
