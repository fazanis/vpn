<?php

use App\Models\Devise;
use App\Models\User;

it('create devise', function () {
    $user=User::factory()->create([
        'name'=>'Ivan',
        'email'=>'Ivan@gmail.com',
    ]);

    $response = $this->actingAs($user)->get('/cabinet');

    $response->assertStatus(200);

    $this->post(route('cabinet.devises.store'),[
        'name'=>'Android',
    ]);
    $this->post(route('cabinet.devises.store'),[
        'name'=>'Android',
    ]);
    $devices = Devise::query()->get();


    $this->assertDatabaseCount('devises',2);


    expect($devices[0]->ui_name)->not->toBe($devices[1]->ui_name);
    expect($devices[0]->ui_id)->not->toBe($devices[1]->ui_id);

});

it('devise page',function(){
    $user=User::factory()->create([
        'name'=>'Ivan',
        'email'=>'Ivan@gmail.com',
        'is_admin' => true
    ]);

    $response = $this->actingAs($user)->get('/cabinet');

    $this->post(route('cabinet.devises.store'),[
        'name'=>'Android',
    ]);
    $devices = Devise::query()->first();

    $response = $this->get(route('subscription.devises',$devices->ui_id));
    $response->assertStatus(200);
});

it('devise delete', function () {
    Queue::fake();
    $user = User::factory()->create(['is_admin' => 1]);
    $device = Devise::factory()->create([
        'user_id' => $user->id,
        'del' => 0,
    ]);

    $this->actingAs($user);

    $response = $this->delete(route('admin.devises.destroy', $device));

    $response->assertRedirect();
    $this->assertDatabaseHas('devises', [
        'id' => $device->id,
        'del' => 1,
    ]);

    Queue::assertPushed(\App\Jobs\DeviseDeleteJob::class);
});
