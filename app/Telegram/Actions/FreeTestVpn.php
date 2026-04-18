<?php

namespace App\Telegram\Actions;

use App\Models\Server;
use App\Models\Subscription;
use App\Models\User;
use App\Services\XuiServices;
use App\Telegram\Bot\Bot;
use App\Telegram\Buttons\InlineButton;
use App\Telegram\Webhook;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FreeTestVpn extends Webhook
{
    public function handle(Request $request)
    {
        $servers = Server::where('id',1)->get();
        $user = User::where('telegram_id',8780472558)->first();
        $hasTrial = Subscription::where('user_id', $user->id)->where('type', 'trial')->first();
        $xuiServices = new XuiServices();
        $r='';
  
        foreach($servers as $server){
            $r.= $xuiServices->getConnect($server,$user);
        }
        return response(base64_encode($r))
        ->header('Content-Type', 'text/plain')
        ->header('Cache-Control', 'no-store')
        ->header('Subscription-Userinfo', 'expire=' . Carbon::parse($user->subscription->expire_at)->timestamp);
       
    }
}
