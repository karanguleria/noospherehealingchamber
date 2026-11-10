<?php

namespace App\Providers;

use App\Models\Answer;
use App\Models\Bodypart;
use App\Models\Element;
use App\Models\Invitation;
use App\Models\Question;
use App\Models\Result;
use App\Models\User;
use App\Observers\InvitationObserver;
use App\Observers\UserObserver;
use App\Policies\AnswerPolicy;
use App\Policies\BodypartPolicy;
use App\Policies\ElementPolicy;
use App\Policies\QuestionPolicy;
use App\Policies\ResultPolicy;
use App\Policies\UserPolicy;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

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
        $this->bootPolicies();
        $this->bootEvents();
        $this->bootRateLimiting();

        Invitation::observe(InvitationObserver::class);
        User::observe(UserObserver::class);
    }

    protected function bootPolicies(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Result::class, ResultPolicy::class);
        Gate::policy(Answer::class, AnswerPolicy::class);
        Gate::policy(Question::class, QuestionPolicy::class);
        Gate::policy(Bodypart::class, BodypartPolicy::class);
        Gate::policy(Element::class, ElementPolicy::class);
    }

    protected function bootEvents(): void
    {
        Event::listen(
            Registered::class,
            SendEmailVerificationNotification::class,
        );
    }

    protected function bootRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
