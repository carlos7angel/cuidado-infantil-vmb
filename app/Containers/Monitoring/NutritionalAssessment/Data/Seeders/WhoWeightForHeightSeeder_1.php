<?php

namespace App\Containers\Monitoring\NutritionalAssessment\Data\Seeders;

use App\Ship\Parents\Seeders\Seeder as ParentSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

final class WhoWeightForHeightSeeder_1 extends ParentSeeder
{
    public function run(): void
    {
        // Verificar si la tabla ya tiene datos
        $exists = DB::table('who_weight_for_height')->exists();
        
        if ($exists) {
            return;
        }

        // Ruta al archivo SQL
        $sqlFile = database_path('SQL/who_weight_for_height.sql');
        
        if (!File::exists($sqlFile)) {
            throw new \RuntimeException("SQL file not found: {$sqlFile}");
        }

        // Leer y ejecutar el archivo SQL
        $sql = File::get($sqlFile);
        
        // Ejecutar el SQL (DB::unprepared permite múltiples statements)
        DB::unprepared($sql);
    }
}

