<?php

namespace App\Containers\Monitoring\Child\Events;

use App\Containers\Monitoring\Child\Models\Child;
use App\Ship\Parents\Events\Event as ParentEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;

final class ChildRequested extends ParentEvent
{
    public function __construct(
        public readonly Child $child,
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
