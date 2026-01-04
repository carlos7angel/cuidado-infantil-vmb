<?php

namespace App\Containers\Monitoring\ChildcareCenter\Events;

use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Ship\Parents\Events\Event as ParentEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;

final class ChildcareCenterUpdated extends ParentEvent
{
    public function __construct(
        public readonly ChildcareCenter $childcarecenter,
    ) {
    }

    /**
     * @return Channel[]
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
