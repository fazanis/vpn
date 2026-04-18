<?php

namespace App\Services;

use App\Models\Devise;
use App\Models\ServerInbound;
use Exception;

class DeviseUpdateServises
{
    /**
     * Create a new class instance.
     */
    public function handle()
    {
        $servers = ServerInbound::query()->with('server')->get();
        foreach($servers as $server){
            $xui = app(XuiServices::class);
            Devise::query()->chunk(100,function ($devises)use($xui,$server) {
                foreach($devises as $devise){
                    try{
                        $response=$xui->addClient($server,$devise);
                        
                        if(!$response['success']){
                            dump($response);
                        }
                    }catch(Exception $e){

                    }
                }
            });
        }
    }
}
