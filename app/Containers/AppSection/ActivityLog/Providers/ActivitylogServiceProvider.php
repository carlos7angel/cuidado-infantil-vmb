<?php

namespace App\Containers\AppSection\ActivityLog\Providers;

use App\Containers\AppSection\ActivityLog\Events\ActivityLogEvent;
use App\Containers\AppSection\ActivityLog\Listeners\ActivityLogListener;
use App\Ship\Parents\Providers\EventServiceProvider as ParentEventServiceProvider;

final class ActivitylogServiceProvider extends ParentEventServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        ActivityLogEvent::class => [
            ActivityLogListener::class,
        ],
    ];

    public function register(): void
    {
    }

    public function boot(): void
    {
        parent::boot();
    }
}
