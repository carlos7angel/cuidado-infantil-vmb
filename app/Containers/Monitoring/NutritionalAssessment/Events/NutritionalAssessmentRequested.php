<?php

namespace App\Containers\Monitoring\NutritionalAssessment\Events;

use App\Containers\Monitoring\NutritionalAssessment\Models\NutritionalAssessment;
use App\Ship\Parents\Events\Event as ParentEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;

final class NutritionalAssessmentRequested extends ParentEvent
{
    public function __construct(
        public readonly NutritionalAssessment $nutritionalassessment,
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
