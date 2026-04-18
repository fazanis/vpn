<?php

namespace App\Telegram\Buttons;

class InlineButton
{
    public static $buttons = [
        'inline_keyboard'=>[]
    ];
    public static function add(mixed $text,string $action, int $row=0)
    {

        return self::$buttons['inline_keyboard'][$row][] = [
            'text'=>$text,
            'callback_data'=>$action,
        ];

    }
    public static function copy(mixed $text,string $url, int $row=0)
    {
        return self::$buttons['inline_keyboard'][$row][]=[
            'text' => $text,
                    'copy_text' => [
                        'text' => $url
                    ]
        ];
    }
    public static function url(mixed $text,string $url, int $row=0)
    {
        return self::$buttons['inline_keyboard'][$row][]=[
            'text' => $text,
                    'url' => $url
        ];
    }
    public static function get(): array
    {
        return array_values(self::$buttons['inline_keyboard']);
    }
    
}
