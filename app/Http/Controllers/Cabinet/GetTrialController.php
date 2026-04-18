<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Admin\ServerController;
use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\Subscription;
use App\Models\Tariff;
use App\Services\XuiServices;
use Exception;
use Illuminate\Http\Request;

class GetTrialController extends Controller
{
    public function __invoke()
    {
        $trial = Subscription::query()->where('user_id',auth()->id())->where('type','trial')->first();
        $tariff = Tariff::where('is_trial', true)->first();

        $xui = new XuiServices();
        $key='';
        dd(Server::query()->get());
        foreach(Server::query()->where('status',1)->get() as $server){
            dd($xui->clientExists($server,auth()->user()));
            if(!$xui->clientExists($server,auth()->user())){
                $key.= $xui->addClient($server,auth()->user());
            }
        }
        dd($key);
        try{
            if(!$trial){
                Subscription::create([
                    'user_id' => auth()->id(),
                    'type' => 'trial',
                    'tariff_id' => $tariff->id,
                    'status' => 'active',
                    'key'=>$key,
                    'expires_at' => now()->addDays($tariff->duration_days),
                ]);
            }

            return back();
        }catch(Exception $e){
            dump($e->getMessage());
        }
        
    }
}
