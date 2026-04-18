<?php

namespace App\Http\Controllers;

use App\Telegram\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WebhookController extends Controller
{
    public function __invoke(Request $request,Webhook $webhook)
    {
        Cache::forever('webhook',$request->all());    
        $webhook->make($request);
        
    }
}
