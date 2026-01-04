<?php

namespace App\Containers\Monitoring\ChildcareCenter\Listeners;

use App\Containers\Monitoring\ChildcareCenter\Events\ChildcareCenterUpdated;
use App\Ship\Parents\Listeners\Listener as ParentListener;
use Illuminate\Contracts\Queue\ShouldQueue;

final class ChildcareCenterUpdatedListener extends ParentListener implements ShouldQueue
{
    public function __construct()
    {
    }

    public function __invoke(ChildcareCenterUpdated $event): void
    {
    }
}
