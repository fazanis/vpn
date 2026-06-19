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

class CastomPayment extends Webhook
{
    public function handle(Request $request)
    {
        $freeKassa = new FreeKassa();
        $telegram_id=$request->input('callback_query')['from']['id'];
        $actions = explode("|",$request->input('callback_query.data'));
        if (count($actions)>1){
            $cena = $actions[1];
        }
//        $user = User::where('telegram_id',$telegram_id)->first();
        Bot::sendPhoto($telegram_id,'Введите сумму',
            (new InlineButton())
                ->add('Назад',Donate::class)
                ->get()
        );
    }
}
