<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\DeviseSincJob;
use App\Models\Server;
use App\Models\ServerInbound;
use App\Services\XuiServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ServerController extends Controller
{
    public function index()
    {
        $servers = Server::query()->with('inbounds')->orderBy('priority')->paginate(15);
        return view('admin.servers.index',compact('servers'));
    }

    public function create()
    {
        return view('admin.servers.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'ip'=>'required',
            'port'=>'required',
            'folder'=>'required',
            'imbound'=>['required','integer']
        ]);
        Server::query()->create($request->all());
        return redirect()->route('admin.servers.index');
    }

    public function edit(Server $server)
    {
        return view('admin.servers.create',compact('server'));
    }

    public function update(Request $request, Server $server)
    {
        $this->validate($request,[
            'name'=>'required',
            'ip'=>'required',
            'port'=>'required',
            'folder'=>'required',
            'imbound'=>['required','integer']
        ]);

        $server->update($request->all());
        return redirect()->route('admin.servers.index');
    }

    public function destroy(Server $server)
    {
        $server->inbounds()->delete();
        $server->delete();
        return back();
    }

    public function deactivated(Server $server)
    {
        if($server->status=='active'){
            $server->update(['status'=>'inactive']);
        }else{
            $server->update(['status'=>'active']);
        }

        return redirect()->route('admin.servers.index');
    }

    public function updateconnect(Server $server)
    {
        $xui = new XuiServices();
        $server->inbounds()->delete();
        $imbounds=$xui->getImbounts($server)['obj'];
        if(is_null($imbounds)){
            $server->inbounds()->delete();
            return back();
        }

        foreach($imbounds as $imbound){

            $response = $xui->getImbount($server,$imbound['id']);
            $streamSettings= json_decode($response['obj']['streamSettings']);
            $settings= json_decode($response['obj']['settings']);
//            dd($streamSettings);
            $array = [
                'inbound'=>$imbound['id'],
                'port'=>$response['obj']['port'],
                'server_id'=>$server->id,
                'protocol'=>$response['obj']['protocol'],
                'type'=>$streamSettings->network,
                'encryption'=>$settings->encryption,
                'security'=>$streamSettings?->security,
                'pbk'=>$streamSettings?->realitySettings?->settings->publicKey,
                'fp'=>$streamSettings?->realitySettings?->settings?->fingerprint,
                'sni'=>$streamSettings?->realitySettings?->serverNames[0],
                'sid'=>$streamSettings?->realitySettings?->shortIds[0],
                'spx'=>$streamSettings?->realitySettings?->settings?->spiderX,
                'pqv'=>$streamSettings?->realitySettings?->settings?->mldsa65Verify,
            ];

            if($streamSettings->network==="xhttp"){
                $array =array_merge($array,[
                'path'=>$streamSettings->xhttpSettings->path,
                'host'=>$streamSettings?->xhttpSettings?->host,
                'mode'=>$streamSettings?->xhttpSettings?->mode,
                ]);

            }

            ServerInbound::query()->updateOrCreate([
                'server_id'=>$server->id,
                'protocol'=>$response['obj']['protocol'],
                'type'=>$streamSettings->network,
            ],$array);

            DeviseSincJob::dispatch()->onQueue('low');
        }

        return back();
    }
}
