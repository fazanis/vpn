<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\Tariff;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $tarifs = Tariff::where('is_trial',0)->get();
        return view('cabinet.subscription',compact('tarifs'));
    }

    public function devises()
    {
                
            $url=env('APP_URL').'/sub/'.auth()->user()->ui_id;
        
            $happLink ='happ://add/' . env('APP_URL').'/connect/'.auth()->user()->ui_id;
    
            return view('vpn.connect', [
                'url' => $url,
                'happLink' => $happLink,
            ]);
    }
}
