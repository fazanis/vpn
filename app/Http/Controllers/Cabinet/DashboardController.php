<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\Devise;
use App\Models\Payment;
use App\Models\Server;
use App\Models\ServerInbound;
use App\Models\Subscription;
use App\Models\Tariff;
use App\Models\User;
use App\Services\Paument\FreeKassa;
use Carbon\Carbon;
use Illuminate\Http\Request;

use function Symfony\Component\Clock\now;

class DashboardController extends Controller
{
    public function index(FreeKassa $kassa)
    {
        $user = auth()->user();
        $user->load('devises','activeSubscription');

        $subscription = Subscription::query()
            ->where('user_id',auth()->id())
            ->latest()->first();
        $payment = Payment::query()->latest()->first();
        $paument_link = $payment ? $kassa->paumentLink($payment->amount, $payment->id) : '';
        $trialUsed = Subscription::query()
            ->where('user_id', auth()->id())
            ->where('type', 'trial')
            ->exists();
        $tarifs = Tariff::query()
            ->when($trialUsed,function ($query){
                $query->where('is_trial',0);
            })
            ->get();

        $devises = Devise::query()->where('del',0)->where('user_id',auth()->id())->get();
        $servers = ServerInbound::query()->with('server')
        ->whereHas('server', function ($q) {
            $q->where('status', 'active');
        })->get();
        $tg_bot = 'https://t.me/'.env('TELEGRAM_BOT_NAME').'?start='.auth()->user()->ui_id;
        return view('cabinet.dashboard',[
            'refers'=>User::where('referred_by',auth()->user()->referral_code)->get(),
            'subscription'=>$subscription,
            'devises'=>$devises,
            'servers'=>$servers,
            'tarifs'=>$tarifs,
            'tg_bot'=>$tg_bot,
            'trialUsed'=>$trialUsed,
            'user'=>$user,
            'payment'=>$payment,
            'paument_link'=>$paument_link
        ]);
    }
}
