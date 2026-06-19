<?php

namespace App\Telegram\Actions\Devise;

use App\Telegram\Actions\Home;
use App\Telegram\Bot\Bot;
use App\Telegram\Buttons\InlineButton;
use App\Telegram\Webhook;
use Illuminate\Http\Request;

class Devise extends Webhook
{
    public function handle(Request $request)
    {
        $telegram_id=$request->input('callback_query')['from']['id'];
        $actions = explode("|",$request->input('callback_query.data'));
        if (count($actions)>1){
            $request = $actions[1];
        }
        $devise = \App\Models\Devise::query()->where('id',$request)->first();
        $buttons = (new InlineButton());
        $buttons->add('Удалить',DeleteDevise::class."|".$devise->id);
        $buttons->add('Назад',MyDevise::class,2,'danger');

        Bot::sendPhoto($telegram_id,"Устройство - {$devise->name} {$devise->trafik}",$buttons->get());
    }
}
