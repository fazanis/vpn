<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Devise;
use App\Models\Server;
use App\Models\ServerInbound;
use App\Services\Xui\DTO\ClientDto;
use App\Services\Xui\Xui;
use App\Services\XuiServices;
use Exception;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use function Laravel\Prompts\password;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $myServers = Server::query()->with('inbounds')->get();
        $xui = new Xui();

        $servers = $xui->servers->status($myServers);
        $online=$xui->servers->online($myServers);

        $inbounds = ServerInbound::query()->with('server')->paginate(10);
        return view('admin.dashboard',compact('inbounds','servers','online'));

    }
}
