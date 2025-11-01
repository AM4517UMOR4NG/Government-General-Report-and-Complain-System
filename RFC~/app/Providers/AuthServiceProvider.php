<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Register FilePolicy for Gate authorization
        Gate::define('view', function ($user, $reportable) {
            return app(\App\Policies\FilePolicy::class)->view($user, $reportable);
        });
        
        Gate::define('download', function ($user, $reportable) {
            return app(\App\Policies\FilePolicy::class)->download($user, $reportable);
        });
        
        Gate::define('preview', function ($user, $reportable) {
            return app(\App\Policies\FilePolicy::class)->preview($user, $reportable);
        });
    }
}
