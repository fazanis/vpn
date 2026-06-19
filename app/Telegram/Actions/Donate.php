<?php

namespace App\Telegram\Actions;

use App\Telegram\Bot\Bot;
use App\Telegram\Buttons\InlineButton;
use App\Telegram\Commands\Start;
use App\Telegram\Webhook;
use Illuminate\Http\Request;

class Donate extends Webhook
{
    public function handle(Request $request)
    {
        $telegram_id=$request->input('callback_query')['from']['id'];
        $buttons = (new InlineButton())
            ->add('100 р',Payment::class."|100")
            ->add('150 р',Payment::class."|150")
            ->add('300 р',Payment::class."|300")
            ->add('1000 р',Payment::class."|1000",2)
            ->add('2000 р',Payment::class."|2000",2)
            ->add('Ввести свою сумму',CastomPayment::class,3)
            ->add('Назад',Home::class,4,'danger')
            ->get();
        Bot::sendPhoto($telegram_id,'Введите сумму',$buttons);
    }
}
