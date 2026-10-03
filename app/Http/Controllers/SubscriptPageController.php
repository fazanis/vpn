<?php

namespace App\Http\Controllers;

use App\Models\Devise;
use App\Models\HappRouting;
use App\Models\Payment;
use App\Models\Server;
use App\Models\ServerInbound;
use App\Models\Subscription;
use App\Models\Tariff;
use App\Models\User;
use App\Services\Paument\FreeKassa;
use App\Services\Xui\XuiFactory;
use App\Services\XuiServices;
use Carbon\Carbon;
use DragonCode\Support\Facades\Helpers\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

use function morphos\English\pluralize;
use function Symfony\Component\Clock\now;

class SubscriptPageController extends Controller
{
    public function index(Request $request,$token,XuiFactory $factory)
    {

        $devise = Devise::query()->with('user')->where('ui_id',$token)->firstOrFail();


        $servers = Server::query()->activate()->orderBy('priority')
            ->get();
        $array=[];

        foreach ($servers as $server) {
            $xui= $factory->make($server);
            $array[] =$xui->subLink($server,$devise);
        }


        $subLink='';
        $text='';

        if(Carbon::now()->startOfDay()->diffInDays($devise->user?->subscription?->latest()->first()->expires_at?->startOfDay())<=1 && $devise->user->is_admin!=1  && !$devise->user->isAdminReferral()){
            $subLink.='vless://00000000-0000-0000-0000-000000000000@127.0.0.1:1'
                .'?type=tcp'
                ."&encryption=none#Подписка скоро истечет \n";
            $text = 'Подписка скоро истечет';
        }
        foreach ($array as $sub) {
            foreach ($sub as $item){
                $subLink.= $item."\n";
            }
        }


        $routing = HappRouting::query()->where('is_active',1)->latest()->first();

        if($routing){
            $route= 'happ://routing/onadd/'.base64_encode($routing->rout);
        }else{
            $route= 'happ://routing/off';
        }
        $subscription=$devise->user?->subscription?->latest()->first();


        $expire=$subscription ? Carbon::parse($subscription?->expires_at)->timestamp : Carbon::now()->addDay(30)->timestamp;

        if($subscription?->status!=1 && $devise->user->is_admin!=1 && !$devise->user->isAdminReferral()){
            $template = 'vless://00000000-0000-0000-0000-000000000000@127.0.0.1:1'
                .'?type=tcp'
                .'&encryption=none#';
            $subLink=$template
                ."⏰ Подписка закончилась \n";
            $subLink.=$template
                .'💳 Продлите на сайте'."\n";
            $subLink.=$template.'🌐 '.env('APP_URL');
        }
//        dd($subLink);
        $userAgent = $request->header('User-Agent');
            if (str_contains($userAgent, 'Happ')  ||
                str_contains($userAgent, 'V2Ray') ||
                str_contains($userAgent, 'v2ray')
                ) {
                return response($route."\n".$subLink)
                    ->header('Content-Type', 'text/plain')
                    ->header('Cache-Control', 'no-store')
                    ->header('Profile-Title', env('APP_NAME'))
                    ->header('Subscription-userinfo', 0)
                    ->header('support-url', 'https://t.me/'.env('TELEGRAM_BOT_NAME').'?start='.$devise->user->ui_id)
                    ->header('announce', 'Если не работает нажмите 🔄'.$text)
                    ->header('profile-web-page-url', env('APP_URL').'/sub/'.$token)
                    ->header('Profile-Update-Interval', '1')
                    ->header('subscription-userinfo','upload=0; download='.$devise->trafik.'; total=0; expire='.$expire);
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
            $tg_bot = 'https://t.me/'.env('TELEGRAM_BOT_NAME').'?start='.$devise->user->ui_id;

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

    public function trialActivate(Tariff $tarif)
    {
        $hasTrial = Subscription::query()
            ->where('type','trial')
            ->where('user_id',auth()->id())
            ->exists();

        if(!$hasTrial){
            Subscription::query()->create([
                'type'=>'trial',
                'tariff_id'=>$tarif->id,
                'max_devices'=>$tarif->max_devices,
                'days'=>$tarif->duration_days,
                'user_id'=>auth()->id(),
                'key'=>'',
                'status'=>Subscription::STATUS_ACTIVE,
                'expires_at'=>Carbon::now()->addDay($tarif->duration_days),
            ]);
            return back()->with('success','Пробный периуд ативирован.');
        }else{
            return back()->with('error','Пробный периуд уже был ативирован, подберите другой тариф');
        }
    }

    public function buy(Request $request,Tariff $tariff,User $user,FreeKassa $kassa)
    {
        if($user->notTrialSubscription?->expires_at>now()){
            return redirect()->route('cabinet.dashboard')->with('warning','У вас еще действует активная подписка. Сменить тариф можно будет после того как она истечет');
        }

           $paument = DB::transaction(function () use ($tariff,$user,$kassa){
                Subscription::query()->firstOrCreate([
                    'type'=>'pay',
                    'user_id'=>$user->id,
                ],[
                    'type'=>'pay',
                    'user_id'=>$user->id,
                    'tariff_id'=>$tariff->id,
                    'status'=>Subscription::STATUS_NOT_PAYMENT,
                    'max_devices'=>$tariff->max_devices,
                    'key'=>'',
                    'days'=>$tariff->duration_days,
                    'expires_at' =>null,
                ]);
                return Payment::query()->create([
                    'user_id'=>$user->id,
                    'tariff_id'=>$tariff->id,
                    'fk_order_id'=>Str::random(16),
                    'amount'=>$tariff->price,
                    'status'=>Subscription::STATUS_NOT_PAYMENT,
                    'paid_at'=>null
                ]);

            });

            return redirect()->away($kassa->paumentLink($tariff->price,$paument->id));

    }

    public function success(Request $request)
    {
        $payment = \App\Models\Payment::query()->where('id',$request->MERCHANT_ORDER_ID)->firstOrFail();
        return view('cabinet.paymnet.success',compact('payment'));
    }
    public function status(Payment $payment)
    {
        abort_unless(
            $payment->user_id === auth()->id(),
            403
        );

        return response()->json([
            'status' => match (true) {
                $payment->paid_at !== null => 'paid',
                $payment->status === -1 => 'failed',
                default => 'pending',
            },
        ]);
    }

    public function extend(Request $request,Tariff $tariff,FreeKassa $kassa)
    {
        $user=auth()->user();

        $user->notTrialSubscription()->update([
            'status'=>Subscription::STATUS_NOT_PAYMENT,
        ]);

        $paument= Payment::query()->create([
            'user_id'=>$user->id,
            'tariff_id'=>$tariff->id,
            'fk_order_id'=>Str::random(16),
            'amount'=>$tariff->price,
            'status'=>Subscription::STATUS_NOT_PAYMENT,
            'paid_at'=>null
        ]);

        return redirect()->away($kassa->paumentLink($tariff->price,$paument->id));
    }
}
