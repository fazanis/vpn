<?php

use App\Models\Devise;
use App\Models\Subscription;
use App\Models\Tariff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
uses(RefreshDatabase::class);



beforeEach(function () {
    $this->user = User::factory()->create([
        'is_admin' => false,
        'referred_by' => null,
    ]);
    $this->admin = User::factory()->create([
        'is_admin' => true,
        'referred_by' => null,
    ]);
});

it('if subscription is active from happ',function() {
    $user = User::factory()->create([
        'is_admin' => false,
        'referred_by' => null,
    ]);
    $tariff = Tariff::query()->where('is_trial',false)->first();
    $devise = Devise::factory()->create(['user_id'=>$user->id]);
    Subscription::factory()->create([
        'user_id'=>$user->id,
        'tariff_id'=>$tariff->id,
        'type'=>'pay',
        'status'=>Subscription::STATUS_ACTIVE,
        'expires_at'=>Carbon::now()->addDays($tariff->duration_days),
    ]);

    $server = \App\Models\Server::factory()->create([
        'name'=>'test',
        'status'=>'active'
    ]);

    $server->inbounds()->create([
        'server_id'=>$server->id,
        'sub_template'=>"vless://00000000-0000-0000-0000-000000000000@127.0.0.1:1?type=tcp&encryption=none#⏰{$server->name}"
    ]);

    $response = $this
        ->withHeaders([
            'User-Agent' => 'Happ',
        ])
        ->get('/sub/'.$devise->ui_id);

    $response->assertOk();
    $response->assertSeeText($server->name);

    $response = $this->withHeaders(['User-Agent' => 'firefox'])
        ->get('/sub/'.$devise->ui_id);

    $response->assertOk();
    $response->assertSee('Подключение устройства');
});

it('if subscription is not active from happ',function() {
    $user = User::factory()->create([
        'is_admin' => false,
        'referred_by' => null,
    ]);
    $tariff = Tariff::query()->where('is_trial',false)->first();
    $devise = Devise::factory()->create([
        'user_id'=>$user->id
    ]);
    Subscription::factory()->create([
        'user_id'=>$user->id,
        'tariff_id'=>$tariff->id,
        'type'=>'pay',
        'status'=>Subscription::STATUS_INACTIVE,
        'expires_at'=>Carbon::now()->addDays($tariff->duration_days),
    ]);


    $server =\App\Models\Server::factory()->create([
        'name'=>'test',
        'status'=>'active'
    ]);



    $response = $this
        ->withHeaders([
            'User-Agent' => 'Happ',
        ])
        ->get('/sub/'.$devise->ui_id);

    $response->assertOk();

    $response->assertSeeText('Подписка закончилась');
    $response->assertSeeText('happ://routing/off');

    \App\Models\HappRouting::query()->create([
        'is_active'=>true,
        'rout'=>'{"Name": "RoscomVPN", "BlockIp": []}',
        'relise_date'=>now()
    ]);

    $response = $this
        ->withHeaders([
            'User-Agent' => 'Happ',
        ])
        ->get('/sub/'.$devise->ui_id);
    $response->assertSeeText('happ://routing/onadd/');
});

it('if admin referal', function () {
    $admin = User::factory()->create(['is_admin'=>true]);
    $user = User::factory()->create(['is_admin'=>false,'referred_by'=>$admin->referral_code]);

    $devise = Devise::factory()->create([
        'user_id'=>$user->id
    ]);

    $response = $this
        ->withHeaders([
            'User-Agent' => 'Happ',
        ])
        ->get('/sub/'.$devise->ui_id);
    $response->assertDontSeeText('Подписка скоро истечет');
    $response->assertDontSeeText('Подписка закончилась');
});

it('if not admin referal not subscription', function () {
    $admin = User::factory()->create(['is_admin'=>true]);
    $user = User::factory()->create(['is_admin'=>false,'referred_by'=>null]);

    $devise = Devise::factory()->create([
        'user_id'=>$user->id
    ]);

    $response = $this
        ->withHeaders([
            'User-Agent' => 'Happ',
        ])
        ->get('/sub/'.$devise->ui_id);

    $response->assertSeeText('Подписка закончилась');
});

it('if not admin referal and end subscription', function () {
    $admin = User::factory()->create(['is_admin'=>true]);
    $user = User::factory()->create(['is_admin'=>false,'referred_by'=>null]);

    $subscription = Subscription::factory()->create([
        'user_id'=>$user->id,
        'status'=>Subscription::STATUS_ACTIVE,
        'expires_at'=>Carbon::now()->addDays(1),
    ]);
    $devise = Devise::factory()->create([
        'user_id'=>$user->id
    ]);

    $response = $this
        ->withHeaders([
            'User-Agent' => 'Happ',
        ])
        ->get('/sub/'.$devise->ui_id);

    $response->assertSeeText('Подписка скоро истечет');
});

it('if active subscription', function () {
    $user = User::factory()->create(['is_admin'=>false,'referred_by'=>null]);
    $subscription = Subscription::factory()->create([
        'user_id'=>$user->id,
        'status'=>Subscription::STATUS_ACTIVE,
        'expires_at'=>now()->addDays(30),
    ]);
    $devise = Devise::factory()->create([
        'user_id'=>$user->id,
    ]);
    $server = \App\Models\Server::factory()->create([
        'name'=>'test',
    ]);
    $inbounnd = \App\Models\ServerInbound::factory()->create([
        'server_id'=>$server->id,
    ]);
    $response = $this
        ->withHeaders([
            'User-Agent' => 'Happ',
        ])
        ->get('/sub/'.$devise->ui_id);

    $response->assertDontSeeText('Подписка скоро истечет');
    $response->assertDontSeeText('Подписка закончилась');
    $response->assertSeeText('test');
    $response->assertSee($inbounnd->sub_template, false);
});
