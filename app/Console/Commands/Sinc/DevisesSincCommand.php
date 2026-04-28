<?php

namespace App\Console\Commands\Sinc;

use App\Models\Devise;
use App\Models\ServerInbound;
use App\Models\SincJobs;
use App\Services\XuiServices;
use Illuminate\Console\Command;

class DevisesSincCommand extends Command
{

    protected $signature = 'devises-sinc-command';
    protected $description = 'Command description';
    public function handle()
    {

        $devises =Devise::query()->get();

        $servers = ServerInbound::query()->with('server')->get();

        $xui = new XuiServices();
        foreach($servers as $server){
            foreach($devises as $devise){

                try{
                    $response=$xui->addClient($server,$devise);

                    if(!$response['success']){
                        dump($response);

                        // continue;
                    }
                }catch(Exception $e){

                }

            }
//           $devise->delete();
        }
    }
}
