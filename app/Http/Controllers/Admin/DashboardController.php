<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Devise;
use App\Models\Server;
use App\Models\ServerInbound;
use App\Services\Xui\DTO\ClientDto;
use App\Services\Xui\Services\ClientService;
use App\Services\Xui\Xui;
use App\Services\Xui\XuiFactory;
use App\Services\XuiServices;
use Exception;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use function Laravel\Prompts\password;

class DashboardController extends Controller
{
    public function __invoke(ClientService $clientService)
    {
        $myServers = Server::query()->with('inbounds')->orderBy('priority')->get();

        foreach($myServers as $server) {
            $xui = XuiFactory::make($server);
            $servers[] = $xui->status($server);

        }
        $online=$clientService->clientCount($myServers);;

        //$xui->servers->online($myServers);

        $devises = Devise::query()->with('user')->orderByDesc('trafik')->paginate(10);
        return view('admin.dashboard',compact('devises','servers','online'));

    }
}
