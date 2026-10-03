<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Subscription;
use DragonCode\Support\Helpers\Str;
use Illuminate\Console\Command;

class OffTarifCommand extends Command
{

    protected $signature = 'off:tarif';


    protected $description = 'Command description';


    public function handle()
    {
        $subscription = Subscription::query()
            ->with('user')
            ->where('status',Subscription::STATUS_ACTIVE)
            ->whereDate('expires_at','<=',now())
            ->first();


            if ($subscription->type==='pay') {
                Payment::query()->create([
                    'user_id'=>$subscription->user_id,
                    'tariff_id'=>$subscription->tariff_id,
                    'amount'=>$subscription->tariff->price,
                    'fk_order_id'=>\DragonCode\Support\Facades\Helpers\Str::random(10),
                    'status'=>Subscription::STATUS_NOT_PAYMENT
                ]);
                $subscription->update(['status'=>Subscription::STATUS_INACTIVE]);
            }
        if ($subscription->type==='trial') {
            $subscription->update(['status' => Subscription::STATUS_INACTIVE]);
        }

        foreach ($subscription->user->devises as $devise) {

        }
    }
}
