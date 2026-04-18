<?php

namespace App\Services;

use App\Models\Devise;
use App\Models\ServerInbound;
use App\Models\SincJobs;
use App\Services\XuiServices;
use Illuminate\Console\Command;

class DevisesCreateServises
{
    public function handle($devise)
    {
        

        $servers = ServerInbound::query()->with('server')->get();
        
            foreach($servers as $server){
                 $xui = app(XuiServices::class);
                try{
                    $response=$xui->addClient($server,$devise);
                    
                    if(!$response['success']){
                        dump($response);
                    }
                }catch(Exception $e){

                }

            }
    }
}
