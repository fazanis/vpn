<?php

namespace App\Telegram;

use App\Telegram\Commands\Help;
use App\Telegram\Commands\NewCommand;
use App\Telegram\Commands\Start;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Webhook
{
    protected const Command=[
        '/start'=>Start::class,
    ];
    public function make(Request $request)
    {

        if (isset($request->all()['message']['entities'][0]['type'])
            && $request->all()['message']['entities'][0]['type']=='bot_command'
        ){
                $command_name = explode(' ', $request->input('message')['text'])[0];
                $command = self::Command[$command_name];
                app($command)->run($request);
        }
        elseif (isset($request->input('callback_query')['data'])){
            app($request->input('callback_query.data'))->handle($request);
        }
    }
}
