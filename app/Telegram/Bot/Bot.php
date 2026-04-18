<?php

namespace App\Telegram\Bot;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class Bot
{
    public static function sendMessage($chat_id,$message)
    {
        Http::post('https://api.telegram.org/bot'.env('TELEGRAM_BOT_TOKEN').'/sendMessage',[
            'chat_id'=>$chat_id,
            'text'=>$message,
            'parse_mode'=>'HTML',
            'reply_markup'=>[
                'remove_keyboard'=>true,
            ]
        ]);
    }

    public static function sendButtons($chat_id,$text,$buttons)
    {
       return Http::post('https://api.telegram.org/bot'.env('TELEGRAM_BOT_TOKEN').'/sendMessage',[
            'chat_id'=>$chat_id,
            'text'=>$text,
           'parse_mode'=>'HTML',
            'reply_markup' => json_encode([
                'inline_keyboard' => $buttons
            ])
        ]);

    }

    public static function sendPhoto($chat_id,$text,$buttons=null)
    {
        $photoPath = Storage::disk('public')->path('images/familivpn.png');

        return Http::attach(
            'photo',
            file_get_contents($photoPath),
            'image.png'
        )->post('https://api.telegram.org/bot'.env('TELEGRAM_BOT_TOKEN').'/sendPhoto',[
            'chat_id'=>$chat_id,
            'caption'=>$text,
            'parse_mode'=>'HTML',
            'reply_markup' => json_encode([
                'inline_keyboard' => $buttons
            ])
        ]);
    }
}
