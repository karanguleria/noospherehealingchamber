<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\Result' => 'App\Policies\ResultPolicy',
        'App\Models\Answer' => 'App\Policies\AnswerPolicy',
        'App\Models\Question' => 'App\Policies\QuestionPolicy',
        'App\Models\Bodypart' => 'App\Policies\BodypartPolicy',
        'App\Models\Element' => 'App\Policies\ElementPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
