<?php

namespace App\Telegram\Commands;

use App\Models\User;
use App\Telegram\Actions\Devise\MyDevise;
use App\Telegram\Actions\Donate;
use App\Telegram\Actions\Help;
use App\Telegram\Actions\TestKey;
use App\Telegram\Bot\Bot;
use App\Telegram\Buttons\InlineButton;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class Start implements CommandInterface
{
    public function run(Request $request)
    {

        try{
            $text=$request->input('message')['text'];
            $telegram_id=$request->input('message')['from']['id'];
            $parts = explode(' ', $text);
            if($parts>1){
                $token = $parts[1] ?? null;
                if($token){
                    $user = User::query()->where('ui_id',$token)->first();
                    $user->update(['telegram_id'=>$telegram_id]);
                }
            }
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

        }catch(Exception $e){
            Cache::forever('error',$e->getMessage());
        }
    }
}
