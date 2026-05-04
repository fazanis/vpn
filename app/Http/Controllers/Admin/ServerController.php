<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\DeviseSincJob;
use App\Models\Server;
use App\Models\ServerInbound;
use App\Services\Xui\Xui;
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

    public function updateconnect(Server $server,Xui $xui)
    {
        $imbounds=$xui->inbounds->getOne($server);
        $xuiIds = collect($imbounds->json('obj'))->pluck('id')->toArray();


//        $server->inbounds()->delete();
//        $imbounds=$xui->getImbounts($server)['obj'];
        ServerInbound::where('server_id',$server->id)->whereNotIn('inbound', $xuiIds)->delete();

        foreach($imbounds->json('obj') as $imbound){
            $streamSettings= json_decode($imbound['streamSettings']);

            $settings= json_decode($imbound['settings']);
            $array = [
                'inbound'=>$imbound['id'],
                'port'=>$imbound['port'],
                'server_id'=>$server->id,
                'protocol'=>$imbound['protocol'],
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
                'protocol'=>$imbound['protocol'],
                'type'=>$streamSettings->network,
            ],$array);

            DeviseSincJob::dispatch($server)->onQueue('low');
        }

        return back();
    }
}
