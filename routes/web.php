<?php

use App\Http\Controllers\Cabinet\Auth\LoginController;
use App\Http\Controllers\Cabinet\Auth\SocialController;
use App\Http\Controllers\Cabinet\DashboardController;
use App\Http\Controllers\Cabinet\DevisController;
use App\Http\Controllers\Cabinet\GetTrialController;
use App\Http\Controllers\Cabinet\SubscriptionController;
use App\Http\Controllers\SubscriptPageController;
use App\Http\Controllers\WebhookController;
use App\Models\User;
use App\Telegram\Actions\Devise\Devise;
use App\Telegram\Buttons\InlineButton;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::any('{any}', function () {
//    return redirect()->away('https://family-nett.ru', 301);
//})->where('any', '.*');

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
//    $telegram_id=423006227;
//    $user = User::query()->where('telegram_id',$telegram_id)->with('devises')->first();
//    $i=0;
//    $buttons = (new InlineButton());
//    foreach ($user->devises as $divise) {
//        $buttons->add($divise->name,Devise::class."|".$divise->id,$i++);
//    }
//    $result = $buttons->get();
//    dd($result);
});
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
Route::post('/paumetn/freekassa/success',function (Request $request){
    dd($request->all());
})->withoutMiddleware([
    \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
]);



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

    Route::get('/', [DashboardController::class,'index'])->name('dashboard');
    Route::get('/get_trial', GetTrialController::class)->name('get_trial');
    Route::get('/subscription', [SubscriptionController::class,'devises'])->name('subscription.devises');
    Route::resource('/devises', DevisController::class);

     Route::post('/logout',function(){
       auth()->logout();
       return redirect()->to('/cabinet');
    })->name('logout');

});

//require __DIR__.'/auth.php';
