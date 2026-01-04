<?php

namespace App\Containers\AppSection\ActivityLog\Listeners;

use App\Containers\AppSection\ActivityLog\Events\ActivityLogEvent;
use App\Containers\AppSection\ActivityLog\Tasks\CreateActivityLogTask;
use App\Ship\Parents\Listeners\Listener as ParentListener;
use Illuminate\Contracts\Queue\ShouldQueue;

final class ActivityLogListener extends ParentListener
{
    public function __construct(
        private readonly CreateActivityLogTask $createActivityLogTask,
    ) {
    }

    public function __invoke(ActivityLogEvent $event): void
    {
        $this->createActivityLogTask->run(
            $event->getLog(),
            $event->getRequest(),
            $event->getModel(),
            $event->getData()
        );
    }
}
