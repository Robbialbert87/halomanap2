<?php

namespace App\Providers;

use App\Events\WorkflowChanged;
use App\Listeners\SendWhatsAppNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind WorkflowService ke service container
        $this->app->singleton(\App\Services\WorkflowService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Super Admin bypass semua permission
        Gate::before(fn ($user) => $user->hasRole('Super Admin') ? true : null);

        // Register Event Listener
        Event::listen(WorkflowChanged::class, SendWhatsAppNotification::class);
    }
}
