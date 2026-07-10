<?php

namespace App\Providers;

use App\Events\WorkflowChanged;
use App\Listeners\SendWhatsAppNotification;
use App\Models\AppNotification;
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

            // Determine role route prefix for notification links
            $roleRoute = null;
            if ($user) {
                $roleGroup = \App\Services\RoleMenuService::getRoleGroup($user);
                $roleRoute = match ($roleGroup) {
                    'kepala_unit' => 'kepala-unit.dispositions.show',
                    'kasi'        => 'kasi.dispositions.show',
                    'kabid'       => 'kabid.dispositions.show',
                    'head_unit'   => 'head-unit.dispositions.show',
                    default       => null,
                };
            }

            // 1. Ticket-based notifications (NEW / DONE / Selesai)
            $unreadNew = Ticket::where('status', 'NEW')
                ->whereNull('notification_seen_at');

            $unreadDone = Ticket::whereIn('status', ['DONE', 'Selesai'])
                ->whereNull('notification_seen_at');

            if ($user && !$user->hasRole(['Super Admin', 'Admin Pengaduan'])) {
                $unreadNew->whereHas('room', fn($q) => $q->where('unit_id', $user->unit_id));
                $unreadDone->whereHas('room', fn($q) => $q->where('unit_id', $user->unit_id));
            }

            $newNotifs = (clone $unreadNew)
                ->with('category')->latest()->take(10)->get()
                ->map(fn($t) => [
                    'id'            => $t->id,
                    'ticket_number' => $t->ticket_number,
                    'title'         => $t->title,
                    'type'          => $t->type,
                    'category'      => $t->category?->name,
                    'time'          => $t->created_at->diffForHumans(),
                    'notif_type'    => 'new',
                    'url'           => route('admin.tickets.show', $t->id),
                ]);

            $doneNotifs = (clone $unreadDone)
                ->with('category')->latest()->take(5)->get()
                ->map(fn($t) => [
                    'id'            => $t->id,
                    'ticket_number' => $t->ticket_number,
                    'title'         => $t->title,
                    'type'          => $t->type,
                    'category'      => $t->category?->name,
                    'time'          => $t->updated_at->diffForHumans(),
                    'notif_type'    => 'selesai',
                    'url'           => route('admin.tickets.show', $t->id),
                ]);

            // 2. Workflow-based notifications (disposisi, eskalasi, etc.)
            $appNotifs = collect();
            $appNotifCount = 0;
            if ($user) {
                $appNotifs = AppNotification::where('user_id', $user->id)
                    ->whereNull('read_at')
                    ->latest()
                    ->take(15)
                    ->get()
                    ->map(function ($n) use ($roleRoute) {
                        $ticketId = $n->data['ticket_id'] ?? 0;
                        $workflowUuid = $n->data['workflow_uuid'] ?? null;

                        if ($roleRoute && $workflowUuid) {
                            $url = route($roleRoute, $workflowUuid);
                        } else {
                            $url = route('admin.tickets.show', $ticketId);
                        }

                        return [
                            'id'            => $ticketId,
                            'ticket_number' => $n->data['ticket_number'] ?? '-',
                            'title'         => $n->title,
                            'type'          => $n->type,
                            'category'      => null,
                            'time'          => $n->created_at->diffForHumans(),
                            'notif_type'    => $n->type === 'pengaduan_selesai' ? 'selesai' : 'new',
                            'url'           => $url,
                        ];
                    });
                $appNotifCount = AppNotification::where('user_id', $user->id)
                    ->whereNull('read_at')
                    ->count();
            }

            $notifications = $newNotifs->concat($doneNotifs)->concat($appNotifs)->sortByDesc('time')->take(15)->values();

            $newCount = (clone $unreadNew)->count();
            $doneCount = (clone $unreadDone)->count();
            $unreadCount = $newCount + $doneCount + $appNotifCount;

            $view->with(compact('unreadCount', 'notifications', 'newCount'));
        });
    }
}
