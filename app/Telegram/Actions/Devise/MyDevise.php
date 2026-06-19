<?php

namespace App\Telegram\Actions\Devise;

use App\Models\User;
use App\Telegram\Actions\Home;
use App\Telegram\Bot\Bot;
use App\Telegram\Buttons\InlineButton;
use App\Telegram\Webhook;
use Illuminate\Http\Request;

class MyDevise extends Webhook
{
    public function handle(Request $request)
    {
        $telegram_id=$request->input('callback_query')['from']['id'];
        $user = User::query()
            ->with('devises')
            ->where('telegram_id',$telegram_id)
            ->first();

        $buttons = (new InlineButton());
        $i=0;
        foreach ($user->devises as $divise) {
            $buttons->add($divise->name,Devise::class."|".$divise->id,$i++);
        }
        $buttons->add('Добавить устройство',Home::class,$i+1,'primary');
        $buttons->add('Назад',Home::class,$i+2,'danger');
        Bot::sendPhoto($telegram_id,'Ваши устройства',$buttons->get());
    }
}
