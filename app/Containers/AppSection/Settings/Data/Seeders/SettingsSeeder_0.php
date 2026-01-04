<?php

namespace App\Containers\AppSection\Settings\Data\Seeders;

use App\Containers\AppSection\Settings\Models\Settings;
use App\Ship\Parents\Seeders\Seeder as ParentSeeder;

class SettingsSeeder_0 extends ParentSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Información del Municipio/Organización
            [
                'key' => 'servidor_municipio',
                'value' => env('SERVER_MUNICIPALITY', ''),
                'type' => 'string',
                'group' => 'municipality',
                'description' => 'Municipio',
            ],
            [
                'key' => 'servidor_departamento',
                'value' => env('SERVER_STATE', ''),
                'type' => 'string',
                'group' => 'municipality',
                'description' => 'Departamento',
            ],
            [
                'key' => 'organizacion_nombre',
                'value' => env('ORGANIZATION_NAME', ''),
                'type' => 'string',
                'group' => 'organization',
                'description' => 'Nombre de la organización',
            ],
            
            // Configuración del Servidor/API
            [
                'key' => 'servidor_estado',
                'value' => env('SERVER_STATUS', 'active'),
                'type' => 'string',
                'group' => 'server',
                'description' => 'Estado del servidor (active, maintenance, etc.)',
            ],
            [
                'key' => 'version_api',
                'value' => env('SERVER_API_VERSION', 'v1'),
                'type' => 'string',
                'group' => 'server',
                'description' => 'Versión de la API',
            ],
            
            // Configuración General
            [
                'key' => 'sistema_nombre',
                'value' => env('APP_NAME', ''),
                'type' => 'string',
                'group' => 'system',
                'description' => 'Nombre del sistema',
            ],
        ];

        foreach ($settings as $setting) {
            Settings::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'group' => $setting['group'],
                    'description' => $setting['description'],
                ]
            );
        }
    }
}

