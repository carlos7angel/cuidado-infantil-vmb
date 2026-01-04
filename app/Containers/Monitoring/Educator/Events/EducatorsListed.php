<?php

namespace App\Containers\Monitoring\Educator\Events;

use App\Ship\Parents\Events\Event as ParentEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;

final class EducatorsListed extends ParentEvent
{
    public function __construct(
        public readonly mixed $educator,
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
