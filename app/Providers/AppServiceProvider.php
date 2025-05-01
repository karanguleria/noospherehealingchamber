<?php

namespace App\Providers;

use App\Models\Invitation;
use App\Models\User;
use App\Observers\InvitationObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\UserObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Invitation::observe(InvitationObserver::class);
        User::observe(UserObserver::class);
    }
}
