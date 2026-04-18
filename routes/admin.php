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



Route::prefix('/admin')->middleware('admin')->name('admin.')->group(function (){
    Route::get('/testmail',function (){
        Mail::raw('Тестовое письмо', function ($message) {
            $message->to('web.master.88@mail.ru')
                ->subject('Test Mail');
        });

        return 'ok';
    })->name('test.mail');
    Route::get('/webhook-data',function(){
        dd(Cache::get('webhook'));
    });
    Route::get('/install-webhook',function (){
    $r =Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/setWebhook",[
        'url'=>env('APP_URL').'/api/webhook'

        ]);
        dd($r->json());
    })->name('install.webhook');
    Route::get('/update/geo',[UpdateGeoController::class,'index'])->name('update.geo');
    Route::get('/',AdminDashboardController::class)->name('dashboard');

    Route::get('/server/deactivated/{server}',[\App\Http\Controllers\Admin\ServerController::class,'deactivated'])->name('server.deactivated');
    Route::get('/server/updateconnect/{server}',[\App\Http\Controllers\Admin\ServerController::class,'updateconnect'])->name('server.updateconnect');
    Route::resource('/servers',\App\Http\Controllers\Admin\ServerController::class);
    Route::prefix('servers/{server}')->group(function () {
        Route::resource('server_inbounds', ServerInboundController::class);
    });
    Route::resource('/users',\App\Http\Controllers\Admin\UserController::class);
    // Route::resource('/users/devises',AdminDevisController::class);
    Route::get('users/{user}/devices', [AdminDevisController::class, 'index'])
        ->name('devises.index');
    Route::get('users/{user}/devices/edit', [AdminDevisController::class, 'edit'])
        ->name('devises.edit');
    Route::get('users/{user}/devices/{device}', [AdminDevisController::class, 'create'])
        ->name('devises.show');
    Route::get('users/{user}/devices/create', [AdminDevisController::class, 'create'])
        ->name('devises.create');
    Route::post('users/{user}/devices/store', [AdminDevisController::class, 'store'])
        ->name('devises.store');
    Route::resource('/tarrifs',\App\Http\Controllers\Admin\TarrifController::class);
});


Route::get('/cabinet/login', [LoginController::class,'index'])->name('login');
Route::post('/cabinet/register',[LoginController::class,'register'])->name('register');
Route::post('/cabinet/login/singin', [LoginController::class,'singin'])->name('login.singin');

Route::get('/email/verify/{id}/{hash}', function (Request $request) {

    $user = User::findOrFail($request->route('id'));



    if (! hash_equals(
        (string) $request->route('hash'),
        sha1($user->getEmailForVerification())
    )) {
        abort(403, 'Invalid verification link');
    }

    if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

    auth()->login($user);
    return redirect('/cabinet')->with('success','Email успешно активирован');
})
// ->middleware(['auth'])
->name('verification.verify');

Route::post('/verification/resend',function(Request $request){

    $user = User::where('email',$request->email)->first();


    event(new Registered($user));
    return redirect()->route('login')
            ->with(['success'=>"Проверьте email {$user->email} для подтверждения аккаунта"]);
})->name('verification.resend');
Route::get('/resend/password',function(){
    return view('cabinet.resend');
})->name('resend.password');
Route::post('/resend/password',function(Request $request){
    $user = User::where('email',$request->email)->first();
    dd($user);
})->name('resend.repassword');
Route::get('/auth/{provider}/redirect',[SocialController::class,'redirect'])->name('google.redirect');
Route::get('/auth/{provider}/callback',[SocialController::class,'callback']);


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
