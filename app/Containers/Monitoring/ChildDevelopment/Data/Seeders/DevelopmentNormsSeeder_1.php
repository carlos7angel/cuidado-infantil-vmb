<?php

namespace App\Containers\Monitoring\ChildDevelopment\Data\Seeders;

use App\Ship\Parents\Seeders\Seeder as ParentSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

final class DevelopmentNormsSeeder_1 extends ParentSeeder
{
    public function run(): void
    {
        // Verificar si la tabla ya tiene datos
        $exists = DB::table('development_norms')->exists();
        
        if ($exists) {
            return;
        }

        // Ruta al archivo SQL
        $sqlFile = database_path('SQL/development_norms.sql');
        
        if (!File::exists($sqlFile)) {
            throw new \RuntimeException("SQL file not found: {$sqlFile}");
        }

        // Leer y ejecutar el archivo SQL
        $sql = File::get($sqlFile);
        
        // Ejecutar el SQL (DB::unprepared permite múltiples statements)
        DB::unprepared($sql);
    }
}

