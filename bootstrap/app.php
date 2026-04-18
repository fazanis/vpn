<?php

use App\Http\Middleware\CabinetMiddleware;
use App\Http\Middleware\SaveReferral;
use App\Telegram\Bot\Bot;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
        // using: function(){
        //     Route::middleware('admin')->group(base_path('routes/admin.php'));
        // }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            SaveReferral::class,
        ]);
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'cabinet' => CabinetMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
           
        })->create();
