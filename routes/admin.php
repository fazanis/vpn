<?php

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
use App\Models\Devise;
use App\Models\Server;
use App\Models\ServerInbound;
use App\Models\Subscription;
use App\Models\User;
use App\Services\Xui\XuiFactory;
use App\Services\XuiServices;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;



Route::prefix('/admin')->middleware('admin')->name('admin.')->group(function (){
    Route::get('/test', function () {
        $servers = Server::query()->whereIn('id',[8,13])->get();
        $devise = Devise::find(1);
        $links = [];
        foreach ($servers as $server){
            $xui = XuiFactory::make($server);
            try {
                $links[] = $xui->subLink($server,$devise);
            }catch (Exception $exception){

            }
        }
        $links = collect($links)
            ->flatten()
            ->implode("\n");
        dd($links);
      $str = "vless://a9de80ea-b2e9-4f8f-bb83-ddaaafb5858f@192.168.2.50:443?encryption=none&extra=%7B%22mode%22%3A%22auto%22%2C%22xPaddingBytes%22%3A%22100-1000%22%7D&fp=firefox&host=&mode=auto&path=%2F&pbk=3Z2x8P1sRsrKFBPqIImrtgPxPXaaDNKpYlq2rnJPwHY&pqv=WzK0Gb3dZG93MINPzfhoP_wTJrtsvviE5Uib0new4dOZOvpO7mgM3y4muaayqtt4HPCxOSMCj8mYTVzk6ZoJfreIxH-3cg_FljD09uQ8KFXO_Q1OGkvtE-CA7ttuIUa04c8Lq6svsmeBWrgu64cEmfBj7_ew6GRoZIYNb4XKyVnJiX9jRKWhZdZEJQ73u6EoftKLoGbzZNNMHaWP2DjJvcbgjxzSSvV1XDzEjSxJEQerLNtyJBmOVeda9Tz_XXhCitjBosZBbHnAESJ4qrwUHILZG2Kk1kLSBpyPGm5THQSky-jx8TvzBsQ3zCbdi_Wp_ykNZfars9gRJWzGCwnAhyU9bJFLSDBtHy6Eg5Jrqe3v8syzdbWUYuMvwu0ibpYJRXFFf-m13X19VBKVYLLMG31bLvLlXqafAkiOe-zWjYbPDVGvfmmhOy4gpqQmnq5gyT96qi5isD3MF0TEYW3BeQW4lhZQIUXLRZC_SGZYFZl1ZDDvEf5f1zVpdOnXjJsyHzlZMwimD40uT68euRRNvZn1XM5Cvg7CXHmOQaIt6NL19A24Nvs5JtQddeoViLS4huvMuuu2S9VBlBsUKDW2uYe8k8Ukb2OQAMNAKq92G1ZDQr3QytfgCjU7Zupc7YhjSMt0XO8vGsrwbd5xGhq0GBrmjqNQO0WapiZpU9upY_ja0Bwta1KVMBcNQ6wTF0zd8_Ok7e4vuabidcT0xKi0ocbbbJaGmLFa0pXwyeuJqYLj-mnugEXnrc86QNdqwkUBwlqYHFDHPhB43Glwb67tm4aeOLBHbx_kfuzrY_WSDx-lgCtpJBSeBypbsbjh5KaGiIzs1x0pITKhkl1BJdK7uWuWcH_W605tY8zgEggE9D2Kr2JH0DXTM8Tpz-IVZ2jXDuX1i4p0xf_DW_S649gP64XbgVUNOr0ahrl6-c5V5hWDUiQ_v5Wnocw8KOtja57ocHhiMg98Y_CE8RTrkXYPlbu4dtsjL8VgbX94kyBH1UGN8tpdTJfQEJKCrsD3poiZe_0o2UZ-eW1o4O8hzY5PnSsbPjP1ob-kudFMjhZeZMvq1PLTVhApght2zxeQAlMFCtMCYBMA_6HAiPiw8Xgu_KlDbCC77SdmVP8Xs6KcYFLCR-IirudgMYCbf4rog12ctFL_0p2o7NRbUErVeavUoasia7mSzvlDPBLyO00_w_qQflryK1NF47I_39c8M-7tNlXeS884WFvobg8ErUYN9b15oLjJHN1PzByJp-BIwNit3g__CD3lpb_v7qZqIO9fsPbvxtnzG6xRGIV_VGieCgVla5Ti0wY1byFyIfxdyQVCNcDZtHRlChsLDq05DTux6VAN7y5_e7b3MtL7sEpFRfyHieZJeqW1SXPkg58KlWpnUQtm_oKeKr_1OlIqQb4jmvF0MJV1kKHDUJ4btIj498TzzIwnpiqwnj2514xoK2TuFzm1B3h4zqtHTjZgXvfDpdH5x7m3HKoJnCwTsvlgr-LU_640SB8_fwq97sRqN5zcWY68QkamlSUFXL2nvAdF9zeyh-BSMvQAVrazBEbMkve8WhSIXowW-DCSp9mC-z28QpdUibKRd2LyZQ3fCtEQfeR8eOdL4wiOEAzVqSEIEabug00hwIAs-9Labbvy5c4NkKFWxEBg_UjLCxvfiAZXgTYUIaK1vfjGYrs-OxmNHPYk3eL5EmuFnF_irBv-BOfpcmo_O6wn0u9kr5AuWJaR4S6VM8g3ih1f3KdJAhah7LFeBqtBite-rfd5kPBPTo2vMn9hJzTr_XjgKie779SdYKoK9PA2C_hp9XADw3zY08Cg_qlbGw11oswyma-tF2ZJgPwbsIw2bp9D2mvD1hJvFOO0AApmE0g2_T58LUbNfnMfhEsTjD_0BIOu6HVSkG1OTj1idVYlsHucoGp5a91pAyz-9IgtnVijQI1h9UOei_G9njlmTpXO_lk9cM_t1P5xUEVrqIxcZb3ofFiKCKnTSpVZcPtkh_EhqXpQzK0SY2PYoZ7kROYUce8OLBmDr-2PA5yP_occmceIRcfUDU9k4Zit4hNwprpmsqUVeQMA0AwykOFLoxiZksm3wtpIlYqinrigSkU4u3fcujgDAau6Qib0Z09QqeEqiQigHArRPrmtEn0o2wCJw7CRXF7VneFN8Xt2wL1ktF5Dhbnd6FMYUt0VX3GQ6TQAu9x0d4PwV9CC96zLAi7Qy8FHav3d7eR4JmVet3JwaI861tRvuM0Zr-UX0rASQcoN-M7zePwqZDoph3e2G6UCqnoGBuY1ltq0gH4Vyo412yenlfbvYGkvx7R8Yf82OizDKBQRoQrsfKN0PFdFsVuQdVt9oTKoLUIcAaSaYfmx1DAZvyu4cLe491MEiYgY4nvJajfpSQL7r7Le88z8JMF2Ingz4nPE6oe-iFxY7dEHwZ4tqJWZDetUnpYIGJr1nanNOlyMjKjJgxtBLuyKqnpz-YLdmzmtyTS271JxFVBKsVM_YySySJQc4TTd_R4A3t9GU7jKUvkI38-87BMxUAQzA09hSGL-pJpCxQaDHMOXrH6zDA71V4WQCZs2_kcxloJEXu3ujSXxhEXEHOVvKoahRjRRnO6D3KU&security=reality&sid=5338&sni=google.com&spx=%2FE0k2I8azE8iDrwY&type=xhttp&x_padding_bytes=100-1000#-android2";
//        dd(parse_url($str));
        function parse(string $rawLink): string
        {
            $parts = parse_url($rawLink);

            $uuid = $parts['user'] ?? null;   // то, что между // и @
            $tag  = isset($parts['fragment']) ? rawurldecode($parts['fragment']) : null;

            $template = $rawLink;

            // Заменяем именно найденную строку, а не по regex —
            // parse_url уже сказал нам точное значение, так что str_replace безопасен
            if ($uuid !== null) {
                $template = str_replace('//' . $uuid . '@', '//{uuid}@', $template);
            }

            if (isset($parts['fragment'])) {
                $template = str_replace('#' . $parts['fragment'], '#{tag}', $template);
            }

            return $template;
        }
        dd(parse($str));
        function templatizeLink(string $rawLink): string
        {
//            return preg_replace('/\/\/[^@]+@/', '//{uuid}@', $rawLink);
            return preg_replace('/#.+$/', '#{tag}', $rawLink);
        }
        dd(templatizeLink($str));
    });
    Route::get('/testmail',function (){
        $to_name = 'Иван';
        $to_email = 'kravchuk001@gmail.com';
        $data = array('name'=>"Sam Jose", "body" => "Test mail");

        $r = Mail::send('emails.emails', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)->subject('Artisans Web Testing Mail');
            $message->from('admin@family-nett.ru','Artisans Web');
        });
        dd($r);
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
    Route::get('/',\App\Http\Controllers\Admin\DashboardController::class)->name('dashboard');

    Route::get('/server/deactivated/{server}',[\App\Http\Controllers\Admin\ServerController::class,'deactivated'])->name('server.deactivated');
    Route::get('/server/resetTraffik',[\App\Http\Controllers\Admin\ServerController::class,'resetTraffik'])->name('reset.traffik');
    Route::get('/server/updateconnect/{server}',[\App\Http\Controllers\Admin\ServerController::class,'updateconnect'])->name('server.updateconnect');
    Route::get('/server/{server}/resyncuser',[\App\Http\Controllers\Admin\ServerController::class,'resyncuser'])->name('servers.resyncuser');
    Route::get('/server/{server}/addInbount',[\App\Http\Controllers\Admin\ServerController::class,'addInbound'])->name('servers.addInbound');
    Route::resource('/servers',\App\Http\Controllers\Admin\ServerController::class);
    Route::prefix('servers/{server}')->group(function () {
        Route::resource('server_inbounds', ServerInboundController::class);
    });
    Route::get('users/delete_not_devise', [\App\Http\Controllers\Admin\UserController::class, 'delete_not_devise'])
        ->name('delete_not_devise');
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
    Route::delete('users/devices/{device}/destroy', [AdminDevisController::class, 'destroy'])
        ->name('devises.destroy');

    Route::get('devices', [AdminDevisController::class, 'allDevise'])->name('all.devises');
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
