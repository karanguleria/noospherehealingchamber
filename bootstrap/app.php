<?php

use App\Http\Middleware\NovaAccessControl;
use App\Http\Middleware\PasswordCheck;
use App\Http\Middleware\RedirectAfterNovaLogin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\EncryptCookies::class,
        ]);

        $middleware->alias([
            'password-check'  => PasswordCheck::class,
            'nova_access'     => NovaAccessControl::class,
            'nova.redirect'   => RedirectAfterNovaLogin::class,
        ]);

        $middleware->trustProxies(
            at: '*',
            headers: \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR |
                     \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST |
                     \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT |
                     \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO |
                     \Illuminate\Http\Request::HEADER_X_FORWARDED_AWS_ELB,
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
