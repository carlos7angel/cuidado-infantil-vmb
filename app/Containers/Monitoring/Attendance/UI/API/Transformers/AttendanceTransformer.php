<?php

namespace App\Containers\Monitoring\Attendance\UI\API\Transformers;

use App\Containers\Monitoring\Attendance\Models\Attendance;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class AttendanceTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(Attendance $attendance): array
    {
        return [
            'type' => $attendance->getResourceKey(),
            'id' => $attendance->getHashedKey(),
            'child_id' => $attendance->child_id,
            'childcare_center_id' => $attendance->childcare_center_id,
            'date' => $attendance->date,
            'status' => $attendance->status?->value,
            'check_in_time' => $attendance->check_in_time?->format('H:i'),
            'check_out_time' => $attendance->check_out_time?->format('H:i'),
            'observations' => $attendance->observations,
            'registered_by' => $attendance->registered_by,
            'created_at' => $attendance->created_at,
            'updated_at' => $attendance->updated_at,
            'readable_created_at' => $attendance->created_at->diffForHumans(),
            'readable_updated_at' => $attendance->updated_at->diffForHumans(),
        ];
    }
}
