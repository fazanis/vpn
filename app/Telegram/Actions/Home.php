<?php

namespace App\Telegram\Actions;

use App\Telegram\Actions\Devise\MyDevise;
use App\Telegram\Bot\Bot;
use App\Telegram\Buttons\InlineButton;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class Home
{
    public function handle(Request $request)
    {
        try{
            $telegram_id=$request->input('callback_query')['from']['id'];
            $buttons = (new InlineButton())
                ->add('📱 Мои устройства',MyDevise::class,3)
                ->add('🆘 Написать в тех потдержку',Help::class,2)
                ->add('💵 Сделать донат разработчику',Donate::class,1,'primary')
                ->get();

            Bot::sendPhoto($telegram_id,
                '👋 Добро пожаловать в FamilyNett — мы подключим Вас к миру

🔒 Почему стоит выбрать FamilyNett?
• Высокая скорость серверов
• Балансировщик нагрузки'
                ,$buttons);

        }catch(\Exception $e){
            Cache::forever('error',$e->getMessage());
        }
    }
}
