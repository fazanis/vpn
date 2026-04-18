<?php

namespace App\Telegram\Actions;

use App\Models\Server;
use App\Models\Subscription;
use App\Models\Tariff;
use App\Models\User;
use App\Services\XuiServices;
use App\Telegram\Bot\Bot;
use App\Telegram\Buttons\InlineButton;
use App\Telegram\Webhook;
use Exception;
use Illuminate\Http\Request;

class GetTrialKey extends Webhook
{
    public function handle(Request $request)
    {
        try{

       
            $user_id = $request->input('callback_query')['from']['id'];
            
            $user = User::query()->where('telegram_id',$user_id)->first();
            $hasTrial = Subscription::where('user_id', $user->id)->where('type', 'trial')->first();

            // $trialKey =(new FreeTestVpn())->handle($request);
            InlineButton::copy('📋 Скопировать',env('APP_URL')."/sub/".$user->ui_id,1);
            InlineButton::url('Открыть инструкцию',env('APP_URL').'/sub/'.$user->ui_id,1);
            InlineButton::add('💳 Купить подписку', Home::class);
            InlineButton::add('🏚 Домой', Home::class);

            if($hasTrial && !now()->greaterThan($hasTrial->expires_at)){
                Bot::sendPhoto($user_id,
                'Пробная версия уже активирована до '.$hasTrial->expires_at.', скопируйте ключь или перейдете к инструкции.',
                InlineButton::get());

                return ;
            }

            if ($hasTrial && now()->greaterThan($hasTrial->expires_at)) {
                
                Bot::sendPhoto($user_id,
                    'Пробный доступ закончился 😔',
                    InlineButton::get()
                );
                return;
            }
            
            $tariff = Tariff::where('is_trial', true)->first();

            Subscription::create([
                'user_id' => $user->id,
                'type' => 'trial',
                'tariff_id' => $tariff->id,
                'status' => 'active',
                'expires_at' => now()->addDays($tariff->duration_days),
            ]);
            $xuiServices =new XuiServices();
            $servers = Server::where('id',1)->get();
            foreach($servers as $server){
                $xuiServices->addClient($server,$user,3);
            }
            
            InlineButton::copy('📋 Скопировать',env('APP_URL')."/sub?token=$user->ui_id",1);
            InlineButton::url('Открыть инструкцию',env('APP_URL').'/sub?token='.$user->ui_id,1);
            InlineButton::add('◀️ Назад',Home::class,2);
            InlineButton::add('🏚 Домой',Home::class,2);
            Bot::sendPhoto($user_id,
            'Пробная версия активирована, скопируйте ключь или перейдете к инструкции',
            InlineButton::get());
         }catch(Exception $e){
            Bot::sendMessage(env('TELEGRAM_BOT_ADMIN'),$e->getMessage());
        }
    }
}
