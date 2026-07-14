<?php

namespace App\Services\Xui;

use App\Models\Server;
use App\Services\Xui\Xui2\Xuiv2Service;
use App\Services\Xui\Xui3\Xuiv3Service;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
/**
 * @method Collection status(Collection $servers)
 * @method \Response getInbounds(Server $server)
 * @method \Response addClient(Server $server, array $data)
 * @method \Response restart(Server $server)
 */
class XuiFactory extends Facade
{
    public function __construct(
        protected Xuiv2Service $xuiv2,
        protected Xuiv3Service $xuiv3,
    )
    {
    }

    public static function make($server)
    {
        if ($server instanceof Collection) {
            foreach ($server as $item){
                $result[] = $item->token ?
                    app(Xuiv3Service::class)
                    : app(Xuiv2Service::class);
            }
            return $result;
        }
        return $server->token ?
            app(Xuiv3Service::class)
            : app(Xuiv2Service::class);
    }


}
