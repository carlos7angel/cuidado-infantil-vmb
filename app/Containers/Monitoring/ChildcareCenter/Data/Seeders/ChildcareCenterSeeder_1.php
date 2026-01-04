<?php

namespace App\Containers\Monitoring\ChildcareCenter\Data\Seeders;

use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Ship\Parents\Seeders\Seeder as ParentSeeder;

final class ChildcareCenterSeeder_1 extends ParentSeeder
{
    public function run(): void
    {
        // ChildcareCenter::firstOrCreate(
        //     ['name' => 'Centro de Cuidado Infantil Principal'],
        //     [
        //         'description' => 'Centro de cuidado infantil dedicado al desarrollo integral de niños y niñas.',
        //         'type' => 'Público',
        //         'date_founded' => '2020-01-15',
        //         'address' => 'Av. Principal #123, Zona Centro',
        //         'phone' => '+591 12345678',
        //         'email' => 'contacto@ccicentral.gob.bo',
        //         'social_media' => 'https://facebook.com/ccicentral',
        //         'logo' => null,
        //         'state' => 'La Paz',
        //         'city' => 'La Paz',
        //         'municipality' => 'Murillo',
        //         'contact_name' => 'María García',
        //         'contact_phone' => '+591 71234567',
        //         'contact_email' => 'maria.garcia@ccicentral.gob.bo',
        //     ]
        // );
    }
}
