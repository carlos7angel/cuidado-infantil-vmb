<?php

namespace App\Containers\Monitoring\Room\UI\API\Transformers;

use App\Containers\Monitoring\Room\Models\Room;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class RoomTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [
        'childcare_center',
        'active_enrollments',
    ];

    public function transform(Room $room): array
    {
        return [
            'type' => $room->getResourceKey(),
            'id' => $room->getHashedKey(),

            // Room information
            'name' => $room->name,
            'description' => $room->description,
            'capacity' => $room->capacity,
            'is_active' => $room->is_active,

            // Related data
            'childcare_center_id' => $room->childcare_center_id,
            'current_children_count' => $room->getCurrentChildrenCount(),
            'available_spots' => $room->getAvailableSpots(),

            // Timestamps
            'created_at' => $room->created_at,
            'updated_at' => $room->updated_at,
            'readable_created_at' => $room->created_at->diffForHumans(),
        ];
    }

    /**
     * Include childcare center.
     */
    public function includeChildcareCenter(Room $room)
    {
        if (!$room->relationLoaded('childcareCenter')) {
            return $this->null();
        }

        return $this->item($room->childcareCenter, function ($center) {
            return [
                'type' => 'childcare_center',
                'id' => $center->getHashedKey(),
                'name' => $center->name,
                'type' => $center->type,
                'state' => $center->state,
                'city' => $center->city,
            ];
        });
    }

    /**
     * Include active enrollments.
     */
    public function includeActiveEnrollments(Room $room)
    {
        if (!$room->relationLoaded('activeEnrollments')) {
            return $this->collection(collect(), function () {
                return [];
            });
        }

        return $this->collection($room->activeEnrollments, function ($enrollment) {
            return [
                'type' => 'enrollment',
                'id' => $enrollment->getHashedKey(),
                'child_id' => $enrollment->child_id,
                'enrollment_date' => $enrollment->enrollment_date,
                'status' => $enrollment->status,
            ];
        });
    }
}