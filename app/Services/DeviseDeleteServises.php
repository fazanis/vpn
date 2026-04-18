<?php

namespace App\Services;

use App\Models\ServerInbound;

class DeviseDeleteServises
{
    /**
     * Create a new class instance.
     */
    public function handle($devise)
    {
        $servers = ServerInbound::query()->with('server')->get();
        
        $xui = new XuiServices();
        foreach($servers as $server){
            try{
        
                $response=$xui->deleteClient($server,$devise);
                
                if(!$response['success']){
                    dump($response);
                }
            }catch(Exception $e){

            }

        }
    }
}
