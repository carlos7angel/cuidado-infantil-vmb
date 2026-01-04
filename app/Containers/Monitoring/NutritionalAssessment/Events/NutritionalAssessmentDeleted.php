<?php

namespace App\Containers\Monitoring\NutritionalAssessment\Events;

use App\Ship\Parents\Events\Event as ParentEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;

final class NutritionalAssessmentDeleted extends ParentEvent
{
    public function __construct(
        public readonly bool $result,
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
