<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\DeviseSincJob;
use App\Models\Devise;
use App\Models\Server;
use App\Models\ServerInbound;
use App\Services\Xui\DTO\InboundsDTO;
use App\Services\Xui\Services\ClientService;
use App\Services\Xui\Xui;
use App\Services\Xui\XuiFactory;
use App\Services\XuiServices;
use Illuminate\Http\Client\ConnectionException;
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
        $server = Server::query()->create($request->all());

        DeviseSincJob::dispatch($server)->onQueue('low');
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

    public function show(Server $server)
    {
        return view('admin.servers.show',compact('server'));
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

    public function updateconnect(Server $server,XuiFactory $xuiFactory)
    {
        try {

        $server->inbounds()->delete();
        $xui=$xuiFactory->make($server);

        $imbounds=$xui->getInbounds($server);

        if ($imbounds==null){
            $server->update(['status'=>'inactive']);
            return back()->with('error',"Сервер не доступен {$server->name}");
        }
        if($imbounds->json('obj')==[]){
            return back()->with('error',"Настройте входящие подключения в нанеле 3x-ui для сервера {$server->name}");
        }


        foreach($xui->subTemplate($server) as $item)
        {
            $server->inbounds()->create(['sub_template'=>$item]);
        }
        DeviseSincJob::dispatch($server)->onQueue('low');
        }catch (ConnectionException $e) {

        }
////        dd($imbounds->json('obj'));
//        if ($imbounds==null){
//            return back()->with('error','Ошибка подключения сервера '.$server->ip);
//        }
//        $xuiIds = collect($imbounds->json('obj'))->pluck('id')->toArray();
//
//        ServerInbound::where('server_id',$server->id)->whereNotIn('inbound', $xuiIds)->delete();
//
//        foreach($imbounds->json('obj') as $imbound){
//            dd($xui->getSubLinksFromImbaund($imbound));
//            $array=InboundsDTO::make($imbound);
////
////                ServerInbound::query()->updateOrCreate([
////                    'server_id'=>$server->id,
////                    'protocol'=>$array->protocol,
////                    'type'=>$array->network,
////                ],(array)$array);
////
////
////            DeviseSincJob::dispatch($server)->onQueue('low');
//        }

        return back()->with('success','Подключение успешно обновлено '.$server->ip);

    }

    public function resyncuser(Server $server)
    {
        DeviseSincJob::dispatch($server)->onQueue('low');
        return back();

    }

    public function addInbound(Server $server,XuiFactory $xuiFactory)
    {
        $xui = $xuiFactory->make($server);
        $xui->createInbound($server);
        DeviseSincJob::dispatch($server)->onQueue('low');
    }

    public function resetTraffik(ClientService $clientService)
    {
        $servers= Server::query()->get();
        $clientService->resetAllTraffics($servers);
        Devise::query()->update(['trafik' => 0]);
        return back();
    }
}
