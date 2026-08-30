<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Quant\Elements\Elements;
use Quant\Interpret\Interpret;
use Quant\Seasons\Seasons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;



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


        Nova::mainMenu(function (Request $request) {
            $menuItems = [];

            // 1. Add Dashboard
            $menuItems[] = MenuSection::dashboard(\App\Nova\Dashboards\Main::class)
                ->icon('view-grid');
          
            if ($request->user() && ($request->user()->type_id == 4 || $request->user()->type_id == 5)) {
                 // 3. Add custom Resources section
            $menuItems[] = MenuSection::make('Resources', [

                // MenuItem::link('Start Quiz', '../../questions'),
                MenuItem::externalLink('Start New Session', url('start-session/'.$request->user()->id)),
                MenuItem::link('Resume Sessions', 'resources/user-sessions'),
                MenuItem::link('History', 'resources/end-sessions'),


            ])->icon('folder')->collapsable();

            }elseif($request->user() && $request->user()->type_id == 3){
                
                 // 3. Add custom Resources section
                    $menuItems[] = MenuSection::make('Resources', [

                        MenuItem::link('Users', '/resources/users'),
             
                    ])->icon('folder')->collapsable();

            }else{
                 // 3. Add custom Resources section
                    $menuItems[] = MenuSection::make('Resources', [

                        MenuItem::link('Clients', '/resources/users'),
                    ])->icon('folder')->collapsable();

            }
           
            

            return $menuItems;
        });


        //Nova::logo('/img/noshpere-healing-new.svg');
        Nova::auth(function ($request) {
            $user = Auth::user();

            // Check if user is not authenticated or has type_id not equal to 2, 3, 4, or 5
            if (!$user || !in_array($user->type_id, [2, 3, 4, 5])) {
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
        Nova::showUnreadCountInNotificationCenter();
        Nova::notificationPollingInterval(10);
        
        Nova::footer(function ($request) {
            return '<p class="text-center"><b>Powered by <a class="link-default" href="https://healingchamber.exponentialhealthcare.com"/>Exponential Healthcare</a> © '. date('Y') .'</b> </p>';
        });
        
        Nova::style('hide-resource', public_path('css/hide-resourse.css'));
        // In NovaServiceProvider.php boot() method or via a custom tool:

        Nova::script('custom-button-label', public_path('js/nova-custom-button.js'));
        
        // Register Nova pages script
        Nova::serving(function ($event) {
            $manifestPath = public_path('build/manifest.json');
            if (file_exists($manifestPath)) {
                $manifest = json_decode(file_get_contents($manifestPath), true);
                if (isset($manifest['resources/js/nova-pages.js']['file'])) {
                    $assetPath = asset('build/' . $manifest['resources/js/nova-pages.js']['file']);
                    Nova::script('nova-pages', $assetPath);
                }
            }
        });
        
        // Add Profile and Change Password links to user dropdown menu
        Nova::userMenu(function (Request $request, \Laravel\Nova\Menu\Menu $menu) {
            return $menu
                ->prepend(
                    MenuItem::link('My Profile', '/profile')
                )
                ->prepend(
                    MenuItem::link('Notifications', '/notifications')
                )
                ->append(
                    MenuItem::link('Change Password', '/change-password')
                );
        });
        
        // Register routes with Nova's router to get sidebar
        $this->app->booted(function () {
            $this->registerNovaCustomRoutes();
        });

    }
    
    /**
     * Register custom Nova routes with sidebar support
     */
    protected function registerNovaCustomRoutes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }
        
        // Register routes with Nova's router so they get the sidebar
        Nova::router(['nova', \Laravel\Nova\Http\Middleware\Authenticate::class])
            ->group(function () {
                Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'novaProfile'])
                    ->name('nova.pages.profile');
                Route::post('/profile', [\App\Http\Controllers\ProfileController::class, 'updateNovaProfile'])
                    ->name('nova.pages.profile.update');
                Route::get('/change-password', [\App\Http\Controllers\ProfileController::class, 'novaChangePassword'])
                    ->name('nova.pages.change-password');
                Route::post('/change-password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])
                    ->name('nova.pages.change-password.update');

                Route::get('/notifications', [\App\Http\Controllers\NotificationsController::class, 'index'])
                    ->name('nova.pages.notifications');
                Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationsController::class, 'markAllRead'])
                    ->name('nova.pages.notifications.read-all');
                Route::delete('/notifications/all', [\App\Http\Controllers\NotificationsController::class, 'destroyAll'])
                    ->name('nova.pages.notifications.destroy-all');
                Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationsController::class, 'markRead'])
                    ->name('nova.pages.notifications.read');
                Route::post('/notifications/{notification}/unread', [\App\Http\Controllers\NotificationsController::class, 'markUnread'])
                    ->name('nova.pages.notifications.unread');
                Route::delete('/notifications/{notification}', [\App\Http\Controllers\NotificationsController::class, 'destroy'])
                    ->name('nova.pages.notifications.destroy');
            });
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

            return $user && in_array($user->type_id, [2, 3, 4, 5]);
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
