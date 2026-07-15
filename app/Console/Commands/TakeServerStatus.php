<?php

namespace App\Console\Commands;

use App\Models\Server;
use App\Services\Xui\XuiFactory;
use App\Telegram\Bot\Bot;
use Illuminate\Console\Command;

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
        $servers = Server::query()->activate()->get();
        foreach ($servers as $server){
            $status = $factory->make($server)->status($server);
            if($status->error){
                Bot::sendMessage(env('TELEGRAM_BOT_ADMIN'), "Ошибка: Сервер  {$server->name} недоступен");
            }
        }
    }
}
