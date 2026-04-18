<?php

use App\Models\User;
use Laravel\Socialite\Socialite;

it('home page', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('redirects guest from cabinet to login', function () {
    $response = $this->get('/cabinet');

    $response->assertRedirect('/cabinet/login');
});

it('shows login page', function () {
    $response = $this->get('/cabinet/login');

    $response->assertStatus(200);

    $response->assertSee('Зайти с Google');
});

it('login from google',function(){
    $this->withSession([]);

    $abstractUser = Mockery::mock(\Laravel\Socialite\Contracts\User::class);

    $abstractUser->shouldReceive('getEmail')->andReturn('test@gmail.com');
    $abstractUser->shouldReceive('getName')->andReturn('Test User');
    $abstractUser->shouldReceive('getId')->andReturn('123');
    $abstractUser->token = 'fake-token';
    $abstractUser->refreshToken = 'fake-refresh-token';

    \Laravel\Socialite\Facades\Socialite::shouldReceive('driver->user')
        ->andReturn($abstractUser);

    $response = $this->get('/auth/google/callback');

    $response->assertRedirect('/cabinet');

    $this->assertAuthenticated(); 


    $this->assertDatabaseHas('users', ['email' => 'test@gmail.com']);
    
    $user = User::where('email', 'test@gmail.com')->first();

    $response = $this->actingAs($user)->get('/cabinet');
    
    $response->assertSee('Добро пожаловать, Test User');
});