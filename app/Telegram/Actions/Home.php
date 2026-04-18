<?php

namespace App\Telegram\Actions;

use App\Telegram\Bot\Bot;
use App\Telegram\Buttons\InlineButton;
use Illuminate\Http\Request;

class Home
{
    public function handle(Request $request)
    {
        InlineButton::add('✨ Бесплатный тест VPN ✨',GetTrialKey::class);
        InlineButton::add('Бесплатный тест впн2',TestKey::class,1);
        Bot::sendPhoto(
            $request->input('callback_query')['from']['id'],
            'Получите бесплатный тест VPN на 4 дня',
            InlineButton::get());
    }
}
