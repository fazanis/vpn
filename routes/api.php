<?php

use App\Http\Controllers\Admin\ServerController;
use App\Models\Server;
use App\Models\Subscription;
use App\Models\Tariff;
use App\Models\User;
use App\Services\XuiServices;
use App\Telegram\Actions\FreeTestVpn;
use App\Telegram\Actions\Home;
use App\Telegram\Actions\TestKey;
use App\Telegram\Bot\Bot;
use App\Telegram\Buttons\InlineButton;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use XUI\Xui;

//$response = Http::withOptions([
//    'verify' => false,
//    ])->post('https://138.124.15.31:46896/4Y6ZHutRkzwR0acv51/login/',[
//    'username'=>'fazanis',
//    'password'=>'Rhfdxer1447'
//]);
//$response = Http::withOptions([
//    'verify' => false,
//])->get('https://138.124.15.31:46896/4Y6ZHutRkzwR0acv51/panel/api/inbounds/get/1');
//$client = Http::withOptions([
//    'verify' => false,
//]);
//
//// создаём cookie jar
//$cookieJar = \GuzzleHttp\Cookie\CookieJar::fromArray([], 'domain.ru');
//
//// 1. ЛОГИН
//$login = $client->withOptions([
//    'cookies' => $cookieJar
//])->post('https://138.124.15.31:46896/4Y6ZHutRkzwR0acv51/login/', [
//    'username' => 'fazanis',
//    'password' => 'Rhfdxer1447'
//]);
//
//// 2. ЗАПРОС С ТОЙ ЖЕ СЕССИЕЙ
//$response = $client->withOptions([
//    'cookies' => $cookieJar
//])->get('https://138.124.15.31:46896/4Y6ZHutRkzwR0acv51/panel/api/inbounds/get/1');
//
//dd($response->json());
//dd($response->json(),$response->getHeaders());


        
    


Route::post('/webhook',\App\Http\Controllers\WebhookController::class);
Route::get('/webhook-data',function (){

    dump(Cache::get('webhook'));
    dump(Cache::get('error'));

});



