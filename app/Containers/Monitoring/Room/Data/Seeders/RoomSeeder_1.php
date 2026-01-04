<?php

namespace App\Containers\Monitoring\Room\Data\Seeders;

use App\Containers\Monitoring\Room\Models\Room;
use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Ship\Parents\Seeders\Seeder as ParentSeeder;
use Illuminate\Support\Facades\Log;

final class RoomSeeder_1 extends ParentSeeder
{
    public function run(): void
    {
        // Get the childcare center that was created in the ChildcareCenterSeeder
        $childcareCenter = ChildcareCenter::where('name', 'Centro de Cuidado Infantil Principal')->first();

        if (!$childcareCenter) {
            Log::warning('RoomSeeder_1: Childcare center "Centro de Cuidado Infantil Principal" not found. Skipping room creation.');
            echo "Warning: Childcare center not found. Skipping room creation.\n";
            return;
        }

        Log::info('RoomSeeder_1: Creating rooms for childcare center: ' . $childcareCenter->name);
        echo "Creating rooms for childcare center: {$childcareCenter->name}\n";

        // Create 3 rooms for the childcare center
        $rooms = [
            [
                'childcare_center_id' => $childcareCenter->id,
                'name' => 'Sala de los Pequeños',
                'description' => 'Sala destinada a niños y niñas de 1 a 3 años de edad',
                'capacity' => 15,
                'is_active' => true,
            ],
            [
                'childcare_center_id' => $childcareCenter->id,
                'name' => 'Sala de los Medianos',
                'description' => 'Sala destinada a niños y niñas de 3 a 5 años de edad',
                'capacity' => 20,
                'is_active' => true,
            ],
            [
                'childcare_center_id' => $childcareCenter->id,
                'name' => 'Sala de los Grandes',
                'description' => 'Sala destinada a niños y niñas de 5 a 7 años de edad',
                'capacity' => 18,
                'is_active' => true,
            ],
        ];

        $createdCount = 0;
        foreach ($rooms as $roomData) {
            $room = Room::firstOrCreate(
                [
                    'childcare_center_id' => $roomData['childcare_center_id'],
                    'name' => $roomData['name']
                ],
                $roomData
            );

            if ($room->wasRecentlyCreated) {
                $createdCount++;
                Log::info("RoomSeeder_1: Created room '{$room->name}' with capacity {$room->capacity}");
                echo "✓ Created room: {$room->name} (Capacity: {$room->capacity})\n";
            } else {
                Log::info("RoomSeeder_1: Room '{$room->name}' already exists");
                echo "○ Room already exists: {$room->name}\n";
            }
        }

        Log::info("RoomSeeder_1: Completed. Created {$createdCount} new rooms.");
        echo "Room seeding completed. Created {$createdCount} new rooms.\n";
    }
}