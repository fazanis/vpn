<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\Devise;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use function Symfony\Component\Clock\now;

class DashboardController extends Controller
{
    public function index()
    {
        $trial_subsription = Subscription::where('type','trial')->where('user_id',auth()->id())->first();
        $subscription =  Subscription::with('tariff')->where('type','trial')->where('user_id',auth()->id())->whereDate('expires_at','>',now())->latest()->first();
        $devises = Devise::query()->where('del',0)->where('user_id',auth()->id())->get();
        return view('cabinet.dashboard',[
            'refers'=>User::where('referred_by',auth()->user()->referral_code)->get(),
            'trial'=>$trial_subsription,
            'subscription'=>$subscription,
            'devises'=>$devises
        ]);
    }
}
