<?php

namespace App\Telegram\Actions;

use App\Models\Server;
use App\Models\Subscription;
use App\Models\Tariff;
use App\Models\User;
use App\Services\Paument\FreeKassa;
use App\Services\XuiServices;
use App\Telegram\Bot\Bot;
use App\Telegram\Buttons\InlineButton;
use App\Telegram\Webhook;
use Exception;
use Illuminate\Http\Request;

class Payment extends Webhook
{
    public function handle(Request $request)
    {
        $freeKassa = new FreeKassa();
        $telegram_id=$request->input('callback_query')['from']['id'];
        $actions = explode("|",$request->input('callback_query.data'));
        if (count($actions)>1){
            $cena = $actions[1];
        }
        Bot::sendPhoto($telegram_id,'Оплатите '. $cena.' рублей',
            (new InlineButton())
                ->url('Перейти к оплате',$freeKassa->paumentLink($cena,1234))
                ->add('Выбрать другую сумму',Donate::class,2,'danger')
                ->add('На главную',Home::class,2,'primary')
                ->get()
        );
    }
}
