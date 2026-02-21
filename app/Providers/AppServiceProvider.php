<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\View;
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
        View::composer('admin.layout.header', function ($view) {
            $notifications = Notification::with(['student', 'parent'])
                ->orderByDesc('sent_at')
                ->limit(5)
                ->get();

            $messages = Notification::with(['student', 'parent'])
                ->whereIn('type', ['broadcast', 'exam'])
                ->orderByDesc('sent_at')
                ->limit(3)
                ->get();

            $view->with([
                'headerNotifications' => $notifications,
                'headerNotificationsCount' => $notifications->count(),
                'headerMessages' => $messages,
                'headerMessagesCount' => $messages->count(),
            ]);
        });
    }
}
