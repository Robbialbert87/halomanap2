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

            // NEW tickets not yet seen
            $unreadNew = Ticket::where('status', 'NEW')
                ->whereNull('notification_seen_at');

            // DONE / Selesai tickets not yet seen
            $unreadDone = Ticket::whereIn('status', ['DONE', 'Selesai'])
                ->whereNull('notification_seen_at');

            if ($user && !$user->hasRole(['Super Admin', 'Admin Pengaduan'])) {
                $unreadNew->whereHas('room', fn($q) => $q->where('unit_id', $user->unit_id));
                $unreadDone->whereHas('room', fn($q) => $q->where('unit_id', $user->unit_id));
            }

            $newCount = (clone $unreadNew)->count();
            $doneCount = (clone $unreadDone)->count();
            $unreadCount = $newCount + $doneCount;

            $newNotifs = (clone $unreadNew)
                ->with('category')->latest()->take(10)->get()
                ->map(fn($t) => [
                    'id' => $t->id,
                    'ticket_number' => $t->ticket_number,
                    'title' => $t->title,
                    'type' => $t->type,
                    'category' => $t->category?->name,
                    'time' => $t->created_at->diffForHumans(),
                    'notif_type' => 'new',
                ]);

            $doneNotifs = (clone $unreadDone)
                ->with('category')->latest()->take(5)->get()
                ->map(fn($t) => [
                    'id' => $t->id,
                    'ticket_number' => $t->ticket_number,
                    'title' => $t->title,
                    'type' => $t->type,
                    'category' => $t->category?->name,
                    'time' => $t->updated_at->diffForHumans(),
                    'notif_type' => 'selesai',
                ]);

            $notifications = $newNotifs->concat($doneNotifs)->sortByDesc('time')->values();

            $view->with(compact('unreadCount', 'notifications', 'newCount'));
        });
    }
}
