<?php

namespace App\Console\Commands;

use App\Models\Server;
use App\Services\Xui\XuiFactory;
use App\Telegram\Bot\Bot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TakeServerStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(XuiFactory $factory)
    {
        $servers = Server::query()->get();
        foreach ($servers as $server){
            $status = $factory->make($server)->status($server);
            if(!$status->error && $server->status==='inactive'){
                $message = $server->name.' снова доступен';
                $this->info($message);
                $server->update(['status' => 'active']);
                $this->sendNotification($message);
            }
            if($status->error && $server->status==='active'){
                $message=$server->name.' не доступен';
                $this->warn($message);
                $server->update(['status' => 'inactive']);
                $this->sendNotification($message);
            }
        }
    }
    public function sendNotification(string $message)
    {
        $result=Bot::sendMessage(env('TELEGRAM_BOT_ADMIN'), $message);
        if($result->getStatusCode()!=200)
        {
            try {
                $toEmail = env('MAIL_ADMIN');
                $toName = env('MAIL_ADMIN_NAME', 'Admin');

                Mail::raw($message, function ($mail) use ($toEmail, $toName) {
                    $mail->to($toEmail, $toName)
                        ->subject('Изменение статуса сервера');

                    $mail->from(
                        env('MAIL_USERNAME'),
                        env('APP_NAME')
                    );
                });

            } catch (\Throwable $e) {
                $this->error("Email error: {$e->getMessage()}");
            }
        }
    }
}
