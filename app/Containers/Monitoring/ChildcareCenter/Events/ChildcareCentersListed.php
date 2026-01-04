<?php

namespace App\Containers\Monitoring\ChildcareCenter\Events;

use App\Ship\Parents\Events\Event as ParentEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;

final class ChildcareCentersListed extends ParentEvent
{
    public function __construct(
        public readonly mixed $childcarecenter,
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
