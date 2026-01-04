<?php

namespace App\Containers\Monitoring\Educator\Data\Seeders;

use App\Containers\AppSection\User\Models\User;
use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Containers\Monitoring\Educator\Models\Educator;
use App\Ship\Parents\Seeders\Seeder as ParentSeeder;

/**
 * Seeder para vincular Educator con User y asignarlo a un ChildcareCenter.
 *
 * Este seeder se ejecuta después de:
 * - EducatorSeeder_1 (crea el Educator)
 * - ChildcareCenterSeeder_1 (crea el centro)
 * - SuperAdminSeeder_2 (crea el usuario educator@educator.com)
 */
final class EducatorAssignmentSeeder_2 extends ParentSeeder
{
    public function run(): void
    {
        // 1. Buscar el usuario educator
        $user = User::where('email', 'educator@educator.com')->first();

        if (!$user) {
            $this->command?->warn('User educator@educator.com not found. Skipping...');
            return;
        }

        // 2. Buscar el educador por DNI
        $educator = Educator::where('dni', '12345678')->first();

        if (!$educator) {
            $this->command?->warn('Educator with DNI 12345678 not found. Skipping...');
            return;
        }

        // 3. Vincular educador con usuario (si no está vinculado)
        if (!$educator->user_id) {
            $educator->update(['user_id' => $user->id]);
            $this->command?->info("Educator '{$educator->full_name}' linked to user '{$user->email}'");
        }

        // 4. Buscar el centro de cuidado infantil
        $childcareCenter = ChildcareCenter::where('name', 'Centro de Cuidado Infantil Principal')->first();

        if (!$childcareCenter) {
            $this->command?->warn('ChildcareCenter not found. Skipping...');
            return;
        }

        // 5. Asignar el educador al centro (si no está asignado)
        if (!$childcareCenter->educators()->where('educator_id', $educator->id)->exists()) {
            $childcareCenter->educators()->attach($educator->id, [
                'assigned_at' => now(),
            ]);
            $this->command?->info("Educator '{$educator->full_name}' assigned to '{$childcareCenter->name}'");
        }
    }
}
