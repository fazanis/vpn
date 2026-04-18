<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\ServerInbound;
use App\Services\XuiServices;
use Exception;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {
         $servers = Server::query()->get();
     
           
        $xui = new XuiServices();
        $server_response=[];
        $online=0;
        foreach($servers as $server){
            $server_response[] = [
                'server'=>$server,
                'status'=>$xui->serverStatus($server) ?? []
            ];
            
            $online+= count($xui->onlineUsers($server)['obj'] ?? []);
            
        }
        // dd($server_response,$online);
        $inbounds = ServerInbound::query()->with('server')->paginate(10);
        return view('admin.dashboard',compact('inbounds','server_response','online'));
      
    }
}
