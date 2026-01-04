<?php

namespace App\Containers\Monitoring\Educator\Events;

use App\Containers\Monitoring\Educator\Models\Educator;
use App\Ship\Parents\Events\Event as ParentEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;

final class EducatorRequested extends ParentEvent
{
    public function __construct(
        public readonly Educator $educator,
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
