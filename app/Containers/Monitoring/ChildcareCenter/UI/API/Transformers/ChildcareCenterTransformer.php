<?php

namespace App\Containers\Monitoring\ChildcareCenter\UI\API\Transformers;

use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class ChildcareCenterTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [
        'rooms',
        'active_rooms',
        'educators',
    ];

    public function transform(ChildcareCenter $childcareCenter): array
    {
        $response = [
            'type' => $childcareCenter->getResourceKey(),
            'id' => $childcareCenter->getHashedKey(),
            'name' => $childcareCenter->name,
            'description' => $childcareCenter->description,
            'type' => $childcareCenter->type,
            'date_founded' => $childcareCenter->date_founded,
            'address' => $childcareCenter->address,
            'phone' => $childcareCenter->phone,
            'email' => $childcareCenter->email,
            'social_media' => $childcareCenter->social_media,
            'logo' => $childcareCenter->logo,
            'state' => $childcareCenter->state,
            'city' => $childcareCenter->city,
            'municipality' => $childcareCenter->municipality,
            'contact_name' => $childcareCenter->contact_name,
            'contact_phone' => $childcareCenter->contact_phone,
            'contact_email' => $childcareCenter->contact_email,
        ];

        // Include pivot data if available (e.g., when accessed through educator relationship)
        if ($childcareCenter->relationLoaded('pivot') && $childcareCenter->pivot) {
            $response['assigned_at'] = $childcareCenter->pivot->assigned_at;
        }

        // Add statistics
        $response['statistics'] = [
            'total_rooms' => $childcareCenter->rooms()->count(),
            'active_rooms' => $childcareCenter->activeRooms()->count(),
            'total_enrollments' => $childcareCenter->enrollments()->count(),
            'active_enrollments' => $childcareCenter->activeEnrollments()->count(),
        ];

        return $response;
    }

    /**
     * Include all rooms.
     */
    public function includeRooms(ChildcareCenter $childcareCenter)
    {
        if (!$childcareCenter->relationLoaded('rooms')) {
            return $this->collection(collect(), function () {
                return [];
            });
        }

        return $this->collection($childcareCenter->rooms, function ($room) {
            return [
                'type' => 'room',
                'id' => $room->getHashedKey(),
                'name' => $room->name,
                'description' => $room->description,
                'capacity' => $room->capacity,
                'is_active' => $room->is_active,
                'current_children_count' => $room->getCurrentChildrenCount(),
                'available_spots' => $room->getAvailableSpots(),
            ];
        });
    }

    /**
     * Include active rooms only.
     */
    public function includeActiveRooms(ChildcareCenter $childcareCenter)
    {
        if (!$childcareCenter->relationLoaded('activeRooms')) {
            return $this->collection(collect(), function () {
                return [];
            });
        }

        return $this->collection($childcareCenter->activeRooms, function ($room) {
            return [
                'type' => 'room',
                'id' => $room->getHashedKey(),
                'name' => $room->name,
                'description' => $room->description,
                'capacity' => $room->capacity,
                'is_active' => $room->is_active,
                'current_children_count' => $room->getCurrentChildrenCount(),
                'available_spots' => $room->getAvailableSpots(),
            ];
        });
    }

    /**
     * Include educators assigned to this childcare center.
     */
    public function includeEducators(ChildcareCenter $childcareCenter)
    {
        if (!$childcareCenter->relationLoaded('educators')) {
            return $this->collection(collect(), function () {
                return [];
            });
        }

        return $this->collection($childcareCenter->educators, function ($educator) {
            return [
                'type' => 'educator',
                'id' => $educator->getHashedKey(),
                'first_name' => $educator->first_name,
                'last_name' => $educator->last_name,
                'email' => $educator->email,
                'assigned_at' => $educator->pivot?->assigned_at,
            ];
        });
    }
}
