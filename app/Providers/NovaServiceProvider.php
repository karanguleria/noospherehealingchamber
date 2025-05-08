<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Quant\Elements\Elements;
use Quant\Interpret\Interpret;
use Quant\Seasons\Seasons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;




class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        //Nova::logo('/img/noshpere-healing-new.svg');
        Nova::auth(function ($request) {
            $user = Auth::user();

            // Check if user is not authenticated or has type_id not equal to 2 or 3
            if (!$user || !in_array($user->type_id, [2, 3])) {
                // Log the user out if unauthorized
                Auth::logout(); 

                // Redirect to login page with error message
                return redirect()->route('nova.login')->withErrors([
                    'email' => 'Access denied. Only authorized users can access Nova.'
                ]);
            }

            return true;  // Grant access if user has type_id 2 or 3
        });
        Nova::withoutThemeSwitcher();
        
        Nova::footer(function ($request) {
            return '<p class="text-center"><b>Powered by <a class="link-default" href="https://healingchamber.exponentialhealthcare.com"/>Exponential Healthcare</a> © '. date('Y') .'</b> </p>';
        });
        
        Nova::style('hide-resource', public_path('css/hide-resourse.css'));
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
               //->middleware(['web', 'nova.redirect'])
               ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            /*return in_array($user->email, [
                  'himekarangulera@gmail.com','mekaranguleria@gmail.com'
            ]) || $user->type_id != 1;*/

            return $user && in_array($user->type_id, [2, 3]);
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new Interpret,
            new Elements,
            new Seasons
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    protected function redirectTo()
    {
        return '/resources/users';
    }
    protected function home()
    {
        return redirect('/nova/resources/users');
    }
}
