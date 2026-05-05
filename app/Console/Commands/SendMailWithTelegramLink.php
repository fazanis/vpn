<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SendMailWithTelegramLink extends Command
{

    protected $signature = 'mail-with';


    protected $description = 'Command description';


    public function handle()
    {
        $users = User::query()->where('email','fazanis@mail.ru')->get();
        foreach ($users as $user){
            $data = ['name'=>$user->name, "telegramLink" => 'https://t.me/'.env('TELEGRAM_BOT_NAME').'?start='.$user->ui_id];
            $to_name = $user->name;
            $to_email = $user->email;

            \Mail::send('emails.subTelegram', $data, function($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->subject('Подпишитесь на телеграм');
                $message->from(env('MAIL_USERNAME'),env('APP_NAME'));
            });
        }

    }
}
