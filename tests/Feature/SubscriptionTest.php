<?php

use App\Models\Devise;
use App\Models\Subscription;
use App\Models\Tariff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
uses(RefreshDatabase::class);

it('refer from admin', function () {
    $ref = '132413132123';
    $admin=User::factory()->create([
        'name'=>'Admin',
        'is_admin' => true,
        'referral_code'=>$ref
    ]);
    $user=User::factory()->create([
        'name'=>'User',
        'referred_by'=>$ref
    ]);
    $this->actingAs($user);
    $response = $this->get('/cabinet');
    $response->assertStatus(200);
    $this->assertTrue(
        $user->isAdminReferral()
    );

    $response->assertSee("Безлимитный доступ");
    $response->assertSee("Ваши устройства");
});

it('refer from user or not refer', function () {
    $ref = '132413132123';
    $admin=User::factory()->create([
        'name'=>'Admin',
        'is_admin' => true,
        'referral_code'=>$ref
    ]);
    $user=User::factory()->create([
        'name'=>'User',
        'referred_by'=>'asdfasdfasdfasd'
    ]);

    $this->actingAs($user);
    $response = $this->get('/cabinet');

    $response->assertStatus(200);
    expect($user->isAdminReferral())->toBeFalse();

    $response->assertSee('Тарифы');
    $response->assertSee('Текущая подписка');
});

it('trial subscription activate', function () {
    Carbon::setTestNow('2026-09-17 12:00:00');
    $user=User::factory()->create([
        'name'=>'User',
    ]);

    $this->actingAs($user);

    $tariff = Tariff::factory()->create([
        'name' => 'Пробный1',
        'is_trial' => true,
        'price' => 0,
        'max_devices' => 5,
        'duration_days' => 30,
    ]);
    $response = $this->get(
        route('cabinet.subscription.trial.activate', $tariff)
    );
    $response->assertRedirect();
    $response->assertSessionHas(
        'success',
        'Пробный периуд ативирован.'
    );
    $this->assertDatabaseHas('subscriptions', [
        'user_id' => $user->id,
        'tariff_id' => $tariff->id,
        'type' => 'trial',
        'status' => Subscription::STATUS_ACTIVE,
        'max_devices' => $tariff->max_devices,
        'days' => $tariff->duration_days,
        'expires_at' => Carbon::now()
            ->addDays($tariff->duration_days)
            ->format('Y-m-d H:i:s'),
    ]);
    expect(
        Subscription::where('user_id', $user->id)
            ->where('type', 'trial')
            ->count()
    )->toBe(1);

    $response = $this->actingAs($user)->get(
        route('cabinet.subscription.trial.activate', $tariff)
    );
    $response->assertRedirect();

    $response->assertSessionHas(
        'error',
        'Пробный периуд уже был ативирован, подберите другой тариф'
    );

    expect(
        Subscription::where('user_id', $user->id)
            ->where('type', 'trial')
            ->count()
    )->toBe(1);

    Carbon::setTestNow();


});

it('trial subscription visable', function () {

    $tarif=Tariff::query()->where('is_trial',true)->first();

    $this->assertDatabaseCount('tariffs', 3);


    $user=User::factory()->create([
        'referred_by'=>null
    ]);
    $this->assertFalse($user->isAdminReferral());
    $this->actingAs($user);
    $response = $this->get('/cabinet');

    $response->assertSee('data-tarif="'.$tarif->id.'"',false);

    $response = $this->get(route('cabinet.subscription.trial.activate',$tarif));

    $response->assertRedirect();

    $response = $this->get('/cabinet');

    $this->assertDatabaseHas('subscriptions', [
        'tariff_id' => $tarif->id,
    ]);


    $this->assertDatabaseHas('subscriptions', [
        'tariff_id'=>$tarif->id
    ]);
    $this->assertDatabaseCount('subscriptions', 1);

    $response->assertDontSee(
        'data-tarif="' . $tarif->id . '"',false
    );
    $response->assertSee(
        'data-sub-id="' . $tarif->id . '"',false
    );

});
it('user can activate trial subscription', function () {
    $tariff = Tariff::query()
        ->where('is_trial', true)
        ->firstOrFail();

    $user = User::factory()->create([
        'referred_by' => null,
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('cabinet.subscription.trial.activate', $tariff));

    $response->assertRedirect();

    $this->assertDatabaseHas('subscriptions', [
        'user_id' => $user->id,
        'tariff_id' => $tariff->id,
        'type' => 'trial',
    ]);
});

it('user can buy paid subscription', function () {
    $tariff = Tariff::factory()->create([
        'is_trial' => false,
        'price' => 500,
        'duration_days' => 30,
        'max_devices' => 4,
    ]);

    $user = User::factory()->create([
        'referred_by' => null,
    ]);

    $this->mock(
        \App\Services\Paument\FreeKassa::class,
        function ($mock) use ($tariff) {
            $mock->shouldReceive('paumentLink')
                ->once()
                ->with($tariff->price, Mockery::type('int'))
                ->andReturn('https://pay.fk.money/test');
        }
    );

    $response = $this
        ->actingAs($user)
        ->post(route('cabinet.subscription.buy', [
            'tariff' => $tariff,
            'user' => $user,
        ]));

    $response->assertRedirect('https://pay.fk.money/test');

    $this->assertDatabaseHas('subscriptions', [
        'user_id' => $user->id,
        'tariff_id' => $tariff->id,
        'type' => 'pay',
        'status' => Subscription::STATUS_NOT_PAYMENT,
    ]);

    $this->assertDatabaseHas('payments', [
        'user_id' => $user->id,
        'tariff_id' => $tariff->id,
        'amount' => $tariff->price,
        'intid' => null,
    ]);
});

it('shows payment checking page', function () {
    $user = User::factory()->create();

    $payment = \App\Models\Payment::factory()->create([
        'user_id' => $user->id,
        'status' => Subscription::STATUS_NOT_PAYMENT,
        'paid_at' => null,
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('payment.freekassa.success', [
            'MERCHANT_ORDER_ID' => $payment->id,
        ]));

    $response->assertOk();
    $response->assertSee('Проверяем оплату');
});

it('activates payment through freekassa events', function () {
    $user = User::factory()->create();

    $tariff = Tariff::factory()->create([
        'is_trial' => false,
        'duration_days' => 30,
    ]);

    $subscription = Subscription::factory()->create([
        'user_id' => $user->id,
        'tariff_id' => $tariff->id,
        'type' => 'pay',
        'status' => Subscription::STATUS_NOT_PAYMENT,
        'days' => 30,
        'expires_at' => now()->subDay(),
    ]);

    $payment = \App\Models\Payment::factory()->create([
        'user_id' => $user->id,
        'tariff_id' => $tariff->id,
        'status' => Subscription::STATUS_NOT_PAYMENT,
        'amount' => $tariff->price,
        'paid_at' => null,
    ]);

    $query = [
        'MERCHANT_ID' => '123',
        'AMOUNT' => $payment->amount,
        'intid' => '321413',
        'MERCHANT_ORDER_ID' => $payment->id,
        'P_EMAIL' => $user->email,
        'CUR_ID' => '13',
        'commission' => '0',
        'SIGN' => 'e7a2dafaaacaf166f03889a694890bc4',
        'us_user' => $user->id,
        'us_tarif' => $tariff->id,
    ];

    $response = $this->get(
        route('payment.freekassa.events') . '?' . http_build_query($query)
    );

    $response->assertOk();
    $response->assertSeeText('ok');

    $this->assertDatabaseHas('payments', [
        'id' => $payment->id,
        'status' => 1,
    ]);

    $this->assertDatabaseHas('subscriptions', [
        'id' => $subscription->id,
        'status' => Subscription::STATUS_ACTIVE,
    ]);
});

it('date from activate traif when trial active', function () {
    $tariff_trial = Tariff::query()->where('is_trial', true)->firstOrFail();
    $tariff_pay = Tariff::query()->where('is_trial', false)->firstOrFail();

    $user = User::factory()->create([
        'referred_by' => null,
    ]);
    $this->actingAs($user);
    $subscription_trial = Subscription::factory()->create([
        'user_id' => $user->id,
        'tariff_id' => $tariff_trial->id,
        'type' => 'trial',
        'status' => Subscription::STATUS_ACTIVE,
        'days' => $tariff_trial->duration_days,
        'expires_at' => now()->addDays($tariff_trial->duration_days),
    ]);


    $subscription = Subscription::factory()->create([
        'user_id' => $user->id,
        'tariff_id' => $tariff_pay->id,
        'type' => 'pay',
        'status' => Subscription::STATUS_NOT_PAYMENT,
        'days' => $tariff_pay->duration_days,
    ]);
    $payment = \App\Models\Payment::factory()->create([
        'user_id' => $user->id,
        'tariff_id' => $tariff_pay->id,
        'status' => Subscription::STATUS_NOT_PAYMENT,
        'amount' => $tariff_pay->price,
        'paid_at' => null,
    ]);

    $query = [
        'MERCHANT_ID' => '123',
        'AMOUNT' => $payment->amount,
        'intid' => '321413',
        'MERCHANT_ORDER_ID' => $payment->id,
        'P_EMAIL' => $user->email,
        'CUR_ID' => '13',
        'commission' => '0',
        'SIGN' => 'e7a2dafaaacaf166f03889a694890bc4',
        'us_user' => $user->id,
        'us_tarif' => $tariff_pay->id,
    ];

    $response = $this->get(
        route('payment.freekassa.events') . '?' . http_build_query($query)
    );
    $response->assertSee('ok');
    $this->assertDatabaseHas('subscriptions', [
        'id'=>$subscription->id,
        'expires_at'=>now()->addDays($tariff_trial->duration_days+$tariff_pay->duration_days)
    ]);
});

it('by tariff if not active', function () {
    $tariff=Tariff::query()->where('is_trial', false)->first();
    $user = User::factory()->create([
        'is_admin'=>false,
    ]);
    $this->actingAs($user);

    $response = $this->post(route('cabinet.subscription.buy',[$tariff,$user]));

    $response->assertRedirect();
    $this->assertDatabaseCount('subscriptions', 1);
    $payment = \App\Models\Payment::first();
    $query = [
        'MERCHANT_ID' => '123',
        'AMOUNT' => $payment->amount,
        'intid' => '321413',
        'MERCHANT_ORDER_ID' => $payment->id,
        'P_EMAIL' => $user->email,
        'CUR_ID' => '13',
        'commission' => '0',
        'SIGN' => 'e7a2dafaaacaf166f03889a694890bc4',
        'us_user' => $user->id,
        'us_tarif' => $tariff->id,
    ];

    $response = $this->get(
        route('payment.freekassa.events') . '?' . http_build_query($query)
    );
    $response->assertSee('ok');
    $this->assertDatabaseHas('subscriptions', [
        'expires_at' => now()->addDay($tariff->duration_days),
    ]);
});

it('validate summ from paument', function () {
    $tariff=Tariff::query()->where('is_trial', false)->first();
    $user = User::factory()->create([
        'is_admin'=>false,
    ]);
    $this->actingAs($user);

    $response = $this->post(route('cabinet.subscription.buy',[$tariff,$user]));

    $response->assertRedirect();
    $this->assertDatabaseCount('subscriptions', 1);
    $payment = \App\Models\Payment::first();
    $query = [
        'MERCHANT_ID' => '123',
        'AMOUNT' => $payment->amount+1,
        'intid' => '321413',
        'MERCHANT_ORDER_ID' => $payment->id,
        'P_EMAIL' => $user->email,
        'CUR_ID' => '13',
        'commission' => '0',
        'SIGN' => 'e7a2dafaaacaf166f03889a694890bc4',
        'us_user' => $user->id,
        'us_tarif' => $tariff->id,
    ];

    $response = $this->get(
        route('payment.freekassa.events') . '?' . http_build_query($query)
    );
    $response->assertSee('false');

});

it('marks expired subscription and disables device management', function () {
    $user = User::factory()->create([
        'referred_by' => null,
    ]);

    $tariff = Tariff::factory()->create([
        'is_trial' => false,
        'max_devices' => 4,
        'duration_days' => 30,
    ]);

    $subscription = Subscription::factory()->create([
        'user_id' => $user->id,
        'tariff_id' => $tariff->id,
        'type' => 'pay',
        'status' => Subscription::STATUS_ACTIVE,
        'expires_at' => now()->subDay(),
        'max_devices' => 4,
        'days' => 30,
    ]);

    // Запускаем ту же команду, которую вызывает cron.
    $this->artisan(\App\Console\Commands\OffTarifCommand::class)
        ->assertExitCode(0);

    // Проверяем, что подписка стала просроченной.
    $this->assertDatabaseHas('subscriptions', [
        'id' => $subscription->id,
        'status' => Subscription::STATUS_INACTIVE,
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('cabinet.dashboard'));

    $response->assertOk();

    $response->assertSee('disabled', false);
});

it('pay if subscription expired', function () {
    $user = User::factory()->create([
        'is_admin'=>false,
    ]);
    $tariff = Tariff::query()->where('is_trial', false)->first();

    $this->actingAs($user);
    $subscription = Subscription::factory()->create([
        'user_id'=>$user->id,
        'status'=>Subscription::STATUS_INACTIVE,
        'type'=>'pay'
    ]);

    $this->post(route('cabinet.subscription.extend',[$tariff,$user]));


    $this->assertDatabaseHas('subscriptions', [
        'id' => $subscription->id,
        'status' => Subscription::STATUS_NOT_PAYMENT,
    ]);
    $this->assertDatabaseHas('payments', [
        'user_id' => $user->id,
        'tariff_id' => $tariff->id,
        'amount' => $tariff->price,
        'status' => Subscription::STATUS_NOT_PAYMENT,
        'paid_at' => null,
    ]);
});
