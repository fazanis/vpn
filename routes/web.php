<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DevisController as AdminDevisController;
use App\Http\Controllers\Admin\ServerInboundController;
use App\Http\Controllers\Admin\UpdateGeoController;
use App\Http\Controllers\Cabinet\Auth\LoginController;
use App\Http\Controllers\Cabinet\DashboardController;
use App\Http\Controllers\Cabinet\DevisController;
use App\Http\Controllers\Cabinet\GetTrialController;
use App\Http\Controllers\Cabinet\Auth\SocialController;
use App\Http\Controllers\Cabinet\SubscriptionController;
use App\Http\Controllers\SubscriptPageController;
use App\Http\Controllers\WebhookController;
use App\Models\Server;
use App\Models\ServerInbound;
use App\Models\Subscription;
use App\Models\User;
use App\Services\XuiServices;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

//Route::any('{any}', function () {
//    return redirect()->away('https://family-nett.ru', 301);
//})->where('any', '.*');

Route::get('/', function () {
    return view('welcome');
});
Route::get('/paumetn/freekassa/events',[SubscriptPageController::class,'events'])->name('payment.freekassa.events');
Route::get('/paumetn/freekassa/success',[SubscriptPageController::class,'success'])->name('payment.freekassa.success');
Route::get('/paumetn/freekassa/filed',function(Request $request){
    dd($request->all());
});
Route::get('/payment/{payment}/status', [SubscriptPageController::class, 'status'])->name('payment.status');

//Route::get('/test', function () {});
//https://family-nett.ru/paumetn/freekassa/events
Route::get('/sub/{token}',[SubscriptPageController::class,'index'])->name('subscription.devises');


//Route::get('/sub/{token}', function ($token) {
//    $response = Http::get("https://family-nett.ru/sub/{$token}");
//
//    return response($response->body(), 200)
//        ->header('Content-Type', $response->header('Content-Type'));
//})->name('subscription.devises');
//Route::get('/sub/{token}', function ($token) {
//    return redirect()->away("https://family-nett.ru/sub/{$token}", 302);
//})->name('subscription.devises');

Route::get('/webhook',WebhookController::class);


Route::get('/admin/login',[\App\Http\Controllers\Admin\LoginController::class,'index'])->name('admin.login');
Route::post('/admin/singin',[\App\Http\Controllers\Admin\LoginController::class,'singin'])->name('admin.singin');
require __DIR__.'/admin.php';

Route::get('/auth/{provider}/redirect',[SocialController::class,'redirect'])->name('google.redirect');
Route::get('/auth/{provider}/callback',[SocialController::class,'callback']);

Route::get('/cabinet/login', [LoginController::class,'index'])->name('login');
Route::post('/cabinet/register',[LoginController::class,'register'])->name('register');
Route::post('/cabinet/login/singin', [LoginController::class,'singin'])->name('login.singin');

Route::get('/email/verify/{id}/{hash}', [LoginController::class,'verify'])->name('verification.verify');

Route::post('/verification/resend',[LoginController::class,'resendMail'])->name('verification.resend');

Route::get('/forgot-password',function(){return view('cabinet.resend');})->name('forgot-password');

Route::post('forgot-password',[LoginController::class,'forgotPassword'])->name('forgot-password');

Route::get('/reset-password/{token}', [LoginController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [LoginController::class, 'passwordUpdate'])->name('password.update');

Route::prefix('/cabinet')->name('cabinet.')
->middleware('cabinet')
->group(function (){
    Route::get('/subscription/trial/activate/{tarif}',[SubscriptPageController::class,'trialActivate'])->name('subscription.trial.activate');
    Route::post('/subscription/{tariff}/{user}/buy',[SubscriptPageController::class,'buy'])->name('subscription.buy');
    Route::post('/subscription/{tariff}/{user}/extend',[SubscriptPageController::class,'extend'])->name('subscription.extend');
    Route::post('/subscription/{payment}/pay',[SubscriptPageController::class,'pay'])->name('subscription.pay');
    Route::get('/', [DashboardController::class,'index'])->name('dashboard');
    Route::get('/get_trial', GetTrialController::class)->name('get_trial');
    Route::get('/tarifs',[\App\Http\Controllers\Cabinet\TarifController::class,'index'])->name('tarifs');
    Route::get('/subscription', [SubscriptionController::class,'devises'])->name('subscription.devises');
    Route::resource('/devises', DevisController::class);

     Route::post('/logout',function(){
       auth()->logout();
       return redirect()->to('/cabinet');
    })->name('logout');

});

//require __DIR__.'/auth.php';
