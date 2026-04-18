<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HappRouting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UpdateGeoController extends Controller
{
    public function index()
    {
        // $route = Http::get('https://raw.githubusercontent.com/hydraponique/roscomvpn-happ-routing/refs/heads/main/HAPP/DEFAULT.DEEPLINK');
        $route = Http::get('https://raw.githubusercontent.com/hydraponique/roscomvpn-happ-routing/refs/heads/main/HAPP/DEFAULT.JSON');
        
        if($route->getStatusCode()===200){
            $relise = json_decode($route->body())->LastUpdated;

            
            $rout = $route->body();
            $old_relise = HappRouting::where('is_active', true)->first();
           
            if(!$old_relise || Carbon::parse($old_relise->relise_date)->gt(Carbon::createFromTimestamp($relise))){
                DB::transaction(function () use ($rout,$relise) {
                    HappRouting::where('is_active', true)
                        ->update(['is_active' => false]);
                
                    HappRouting::create([
                        'rout' => $rout,
                        'is_active' => true,
                        'relise_date'=>Carbon::createFromTimestamp($relise)->format('Y-m-d H:i:s')
                    ]);
                });
            }
            
            
           
        }
      return back();
    }
}
