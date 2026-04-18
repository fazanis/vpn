<?php

namespace App\Console\Commands\Sinc;

use App\Models\Devise;
use App\Models\ServerInbound;
use App\Models\SincJobs;
use App\Services\XuiServices;
use Illuminate\Console\Command;

class DevisesUnSincCommand extends Command
{
    
    protected $signature = 'devises-unsinc-command';
    protected $description = 'Command description';
    public function handle()
    {
        
        $devises =SincJobs::query()->where('command',DevisesUnSincCommand::class)->get();

        $servers = ServerInbound::query()->with('server')->get();
        
        $xui = new XuiServices();
        foreach($devises as $devise){
            foreach($servers as $server){
                try{
            
                    $response=$xui->deleteClient($server,$devise->device);
                 
                    if(!$response['success']){
                        dump($response);
                        
                        // continue;
                    }
                }catch(Exception $e){

                }

            }
            $devise->device->delete();
           $devise->delete();
        }
    }
}
