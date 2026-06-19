<?php

namespace App\Telegram\Buttons;

class InlineButton
{
    public $buttons = [
        'inline_keyboard'=>[]
    ];
    public function add(mixed $text,string $action, int $row=0,string $style='')
    {

        $this->buttons['inline_keyboard'][$row][] = [
            'text'=>$text,
            'callback_data'=>$action,
            'style'=>$style
        ];
        return $this;
    }
    public function copy(mixed $text,string $url, int $row=0,string $style='')
    {
        $this->buttons['inline_keyboard'][$row][]=[
            'text' => $text,
            'style'=>$style,
            'copy_text' => [
                'text' => $url
            ]
        ];
        return $this;
    }
    public function url(mixed $text,string $url, int $row=0,string $style='')
    {
        $this->buttons['inline_keyboard'][$row][]=[
            'text' => $text,
            'url' => $url,
            'style'=>$style
        ];
        return $this;
    }
    public function get(): array
    {
        return array_values($this->buttons['inline_keyboard']);
    }

}
