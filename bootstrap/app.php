<?php

use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use App\Http\Middleware\Admin;
use App\Http\Middleware\User;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->encryptCookies(except: ['appearance']);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
        ]);

       /*  $middleware->api(append: [
            Admin::class,
            User::class
        ]); */

         $middleware->alias([
            'user' => \App\Http\Middleware\User::class, // Middleware vacío (solo para rutas públicas)
            'admin' => \App\Http\Middleware\Admin::class, // Middleware de admin
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
