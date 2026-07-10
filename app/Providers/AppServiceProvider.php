<?php

namespace App\Providers;

use App\Events\WorkflowChanged;
use App\Listeners\SendWhatsAppNotification;
use App\Models\Ticket;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
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

        // Share notification data with header component
        View::composer(['components.header', 'layouts.admin'], function ($view) {
            $user = auth()->user();
            $unread = Ticket::where('status', 'NEW')
                ->whereNull('notification_seen_at');

            if ($user && !$user->hasRole(['Super Admin', 'Admin Pengaduan'])) {
                $unread->whereHas('room', function ($q) use ($user) {
                    $q->where('unit_id', $user->unit_id);
                });
            }

            $unreadCount = (clone $unread)->count();
            $notifications = (clone $unread)
                ->with('category')
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($ticket) {
                    return [
                        'id' => $ticket->id,
                        'ticket_number' => $ticket->ticket_number,
                        'title' => $ticket->title,
                        'type' => $ticket->type,
                        'category' => $ticket->category?->name,
                        'time' => $ticket->created_at->diffForHumans(),
                    ];
                });
            $view->with(compact('unreadCount', 'notifications'));
        });
    }
}
