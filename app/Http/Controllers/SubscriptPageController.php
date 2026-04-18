<?php

namespace App\Http\Controllers;

use App\Models\Devise;
use App\Models\HappRouting;
use App\Models\Server;
use App\Models\ServerInbound;
use App\Models\Subscription;
use App\Models\User;
use App\Services\XuiServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

use function Symfony\Component\Clock\now;

class SubscriptPageController extends Controller
{
    public function index(Request $request,$token)
    {
        
        $devise = Devise::where('ui_id',$token)->first();
        $connects = ServerInbound::query()
        ->select('server_inbounds.*')
        ->join('servers', 'servers.id', '=', 'server_inbounds.server_id')
        ->orderBy('servers.priority')->get();
        // dd($devise);
        $array = [];
        foreach($connects as $connect){
            $array[]=$connect->protocol
            .'://'.$devise->ui_name
            .'@'.$connect->server->ip
            .':'.$connect->port
            .'?type='.$connect->type
            .'&encryption='.$connect->encryption
            .'&security='.$connect->security
            .'&pbk='.$connect->pbk
            .'&fp='.$connect->fp
            .'&sni='.$connect->sni
            .'&sid='.$connect->sid
            .'&spx='.$connect->spx
            .'&pqv='.$connect->pqv
            .'&pqv='.$connect->pqv
            .'#'.$connect->server->name
            .''.$connect->server->flag
            ;
            
         }
        
            
         $result = implode("\n",$array);
        $routing = HappRouting::query()->where('is_active',1)->latest()->first();
 
        if($routing){
            $route= 'happ://routing/onadd/'.base64_encode($routing->rout);
        }else{
            $route= 'happ://routing/off';
        }

        
        $userAgent = $request->header('User-Agent');
            if (str_contains($userAgent, 'Happ')  ||
                str_contains($userAgent, 'V2Ray') ||
                str_contains($userAgent, 'v2ray')
                ) {
                return response($route."\n".$result)
                    ->header('Content-Type', 'text/plain')
                    ->header('Cache-Control', 'no-store')
                    ->header('Profile-Title', env('APP_NAME'))
                    ->header('Subscription-userinfo', 0)
                    ->header('support-url', 'https://t.me/'.env('TELEGRAM_BOT_NAME').'?start='.$devise->user->ui_id)
                    ->header('announce', 'Если не работает нажмите 🔄')
                    ->header('profile-web-page-url', env('APP_URL').'/sub/'.$token)
                    ->header('Profile-Update-Interval', '1')
                    ->header('subscription-userinfo','upload=0; download=50000; total=0; expire='. Carbon::now()->addDay(30)->timestamp);
                    // ->header('subscription-userinfo','upload=0; download=20000; total=0; expire='. Carbon::parse($user->subscription->expires_at)->timestamp);
                    
            }
            
             
            $userAgent = request()->header('User-Agent');

            if (str_contains($userAgent, 'Android')) {
                $platform = 'Android';
            } elseif (str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad')) {
                $platform = 'iOS';
            } elseif (str_contains($userAgent, 'Windows')) {
                $platform = 'Windows';
            } else {
                $platform = 'Other';
            }
            
                    

            $url=env('APP_URL').'/sub/'.$token;
        
            $happLink ='happ://add/' . env('APP_URL').'/sub/'.$token;
            $v2Link =env('APP_URL').'/sub/'.$token;
            $tg_bot = 'https://t.me/True2VpnBot?start='.$devise->user->ui_id;
      
            return view('vpn.connect', [
                'devise'=>$devise,
                'url' => $url,
                'happLink' => $happLink,
                'v2Link' => $v2Link,
                'platform'=>$platform,
                'tg_bot'=>$tg_bot
            ]);
        
        
    }
    
    public function happConnect(Request $request,$token)
    {
        $servers = Server::where('status','active')->orderBy('priority')->get();
        $user = User::where('ui_id',$token)->first();
        $hasTrial = Subscription::where('user_id', $user->id)->where('type', 'trial')->first();
        
        $xuiServices = new XuiServices();
        $result=[];
        
        foreach($servers as $server){
            if(!$xuiServices->clientExists($server,$user)){
                $xuiServices->addClient($server,$user);
            }
            $result[]= $xuiServices->getConnect($server,$user).'#'.$server->name.''.$server->flag;
            $traff=+$xuiServices->clientTraffikById($server,$user)['obj'][0]['allTime'];
        }
        
      $result = implode("\n", $result);
        
      return response(base64_encode($result))
            ->header('Content-Type', 'text/plain')
            ->header('Cache-Control', 'no-store')
            ->header('Profile-Title', 'FamiliVpn')
            ->header('Subscription-userinfo', 0)
            ->header('announce', 'Если не работает нажмите 🔄')
            ->header('profile-web-page-url', env('APP_URL').'/sub/'.$token)
            ->header('support-url', 'https://t.me/True2VpnBot')
            ->header('Profile-Update-Interval', '1')
            ->header('subscription-userinfo','upload=0; download='.$traff.'; total=0; expire='. Carbon::parse($user->subscription->expires_at)->timestamp);

    }
}
