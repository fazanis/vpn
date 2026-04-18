<?php

namespace App\Telegram\Commands;

use App\Models\Tariff;
use App\Models\User;
use App\Telegram\Actions\FreeTestVpn;
use App\Telegram\Actions\GetTrialKey;
use App\Telegram\Actions\TestKey;
use App\Telegram\Bot\Bot;
use App\Telegram\Buttons\InlineButton;
use App\Telegram\Webhook;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class Start implements CommandInterface
{
    public function run(Request $request)
    {
        Cache::forever('webhook',$request->all());
        try{
            $text=$request->input('message')['text'];
            $telegram_id=$request->input('message')['from']['id'];
            $parts = explode(' ', $text);
            if($parts>1){
                $token = $parts[1] ?? null;
                if($token){
                    User::query()->where('ui_id',$token)->update(['telegram_id'=>$telegram_id]);
                    InlineButton::add('Написать в тех потдержку',GetTrialKey::class);
                    Bot::sendPhoto($request->input('message')['from']['id'],'Поздравляем вас с успешной регистрацией',InlineButton::get());
                    return;
                }
            }
            Bot::sendMessage($telegram_id,'Добро пожаловать');
        // User::query()->createOrFirst([
        //     'telegram_id'=>$request->input('message')['from']['id']
        // ],[
        //     'name'=>$request->input('message')['from']['first_name'],
        //     'email'=>$request->input('message')['from']['username'],
        //     'telegram_id'=>$request->input('message')['from']['id'],
        // ]);
        }catch(Exception $e){
            Cache::forever('error',$e->getMessage());
        }
        
        // $tariff= Tariff::where('is_trial')->first();
        // InlineButton::add('✨ Бесплатный тест VPN ✨',GetTrialKey::class);
        // InlineButton::add('Бесплатный тест впн2',FreeTestVpn::class,1);
        // $template = (string)view('bot.index',['name'=>$request->input('message')['from']['first_name'],'tariff'=>$tariff]??'' );
        // Bot::sendPhoto($request->input('message')['from']['id'],$template,InlineButton::get());
    }
}
