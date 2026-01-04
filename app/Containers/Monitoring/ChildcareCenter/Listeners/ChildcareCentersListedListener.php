<?php

namespace App\Containers\Monitoring\ChildcareCenter\Listeners;

use App\Containers\Monitoring\ChildcareCenter\Events\ChildcareCentersListed;
use App\Ship\Parents\Listeners\Listener as ParentListener;
use Illuminate\Contracts\Queue\ShouldQueue;

final class ChildcareCentersListedListener extends ParentListener implements ShouldQueue
{
    public function __construct()
    {
    }

    public function __invoke(ChildcareCentersListed $event): void
    {
    }
}
