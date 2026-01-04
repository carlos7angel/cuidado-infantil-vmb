<?php

namespace App\Containers\AppSection\User\Data\Seeders;

use App\Ship\Parents\Seeders\Seeder as ParentSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder para crear los oauth_clients de Laravel Passport 12+.
 *
 * Esto evita tener que ejecutar `php artisan passport:install`
 * después de cada `migrate:fresh` durante el desarrollo.
 *
 * Para usar en la app:
 *   client_id: 9dde1c00-0000-0000-0000-000000000001
 *   client_secret: vmb2025@-app
 *
 * ⚠️ En producción: usar passport:install para generar secrets seguros
 */
final class PassportClientsSeeder_0 extends ParentSeeder
{
    // UUID fijo para desarrollo (predecible)
    private const PERSONAL_ACCESS_CLIENT_ID = '9dde1c00-0000-0000-0000-000000000001';
    // Secret en texto plano (lo que usas en la app)
    private const PERSONAL_ACCESS_CLIENT_SECRET = 'vmb2025@-app';

    public function run(): void
    {
        $this->createPersonalAccessClient();

        $this->command?->info('Passport clients created successfully!');
        $this->command?->info('Client ID: ' . self::PERSONAL_ACCESS_CLIENT_ID);
        $this->command?->info('Client Secret: ' . self::PERSONAL_ACCESS_CLIENT_SECRET);
    }

    private function createPersonalAccessClient(): void
    {
        $clientId = self::PERSONAL_ACCESS_CLIENT_ID;

        // Verificar si ya existe
        if (DB::table('oauth_clients')->where('id', $clientId)->exists()) {
            return;
        }

        // En Passport 12+ el secret debe estar hasheado con Bcrypt (formato $2y$...)
        $hashedSecret = bcrypt(self::PERSONAL_ACCESS_CLIENT_SECRET);

        // Estructura para Passport 12+ (Laravel 11+)
        DB::table('oauth_clients')->insert([
            'id' => $clientId,
            'owner_type' => null,
            'owner_id' => null,
            'name' => 'Personal Access Client (App) - ' . config('app.name'),
            'secret' => $hashedSecret,
            'provider' => 'users',
            'redirect_uris' => '[]',
            'grant_types' => json_encode(['password', 'refresh_token']),
            'revoked' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
