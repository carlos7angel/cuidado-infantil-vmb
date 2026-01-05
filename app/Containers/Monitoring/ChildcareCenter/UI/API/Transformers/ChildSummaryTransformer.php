<?php

namespace App\Containers\Monitoring\ChildcareCenter\UI\API\Transformers;

use App\Containers\Monitoring\ChildEnrollment\Models\ChildEnrollment;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;
use Vinkla\Hashids\Facades\Hashids;

final class ChildSummaryTransformer extends ParentTransformer
{
    /**
     * Relations that are always included (Apiato/Fractal will auto-load these).
     */
    protected array $defaultIncludes = [
        
    ];

    protected array $availableIncludes = [];

    /**
     * Transform child to summary format with only essential identification data.
     */
    public function transform(ChildEnrollment $childEnrollment): array
    {
        $child = $childEnrollment->child;
        // $room = $childEnrollment->room;

        return [
            'type' => $childEnrollment->getResourceKey(),
            'id' => $child->getHashedKey(),
            'general' => [
                'first_name' => $child->first_name,
                'paternal_last_name' => $child->paternal_last_name,
                'maternal_last_name' => $child->maternal_last_name,
                'full_name' => $child->full_name,
                'birth_date' => $child->birth_date,
                'age' => $child->age,
                'gender' => $child->gender,
                'state' => $child->state,
                'city' => $child->city,
                'address' => $child->address,
            ],
            
            'active_enrollment' => (object) [
                'data' => (object) [
                    'enrollment_date' => $childEnrollment->enrollment_date,
                    'childcare_center_id' => Hashids::encode($childEnrollment->childcare_center_id),
                    'room_id' => Hashids::encode($childEnrollment->room_id ?? null),
                    'room_name' => $childEnrollment->room?->name,
                    'status' => $childEnrollment->status,
                ]
            ],
            
        ];
    }

}

