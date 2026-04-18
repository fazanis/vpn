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
    ]);
    
    $response = $this->actingAs($user)->get('/cabinet');

    $this->post(route('cabinet.devises.store'),[
        'name'=>'Android',
    ]);
    $devices = Devise::query()->first();
    
    $response = $this->get(route('subscription.devises',$devices->ui_id));
    $response->assertStatus(200);
});