<?php

namespace App\Console\Commands;

use App\Models\Server;
use App\Models\ServerInbound;
use App\Models\User;
use App\Services\XuiServices;
use Exception;
use Illuminate\Console\Command;

class SincKeyFromUsers extends Command
{
    protected $signature = 'sinc:key';
    protected $description = 'Command description';
    public function handle()
    {
        $users = User::query()->where('is_admin',0)->get();
        
        $servers = ServerInbound::query()->with('server')->get();
        
        $xui = new XuiServices();
        foreach($users as $user){
            foreach($servers as $server){
                try{
                    $response=$xui->addClient($server,$user);
                    if(!$response['success']){
                        dump($response);
                        // continue;
                    }
                }catch(Exception $e){

                }

            }
           
        }
        
    }
}
