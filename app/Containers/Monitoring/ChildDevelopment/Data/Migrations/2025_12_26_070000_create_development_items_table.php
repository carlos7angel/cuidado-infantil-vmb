<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('development_items', static function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('item_number'); // Número del ítem en el formulario
            $table->unsignedSmallInteger('age_min_months'); // Edad mínima en meses
            $table->unsignedSmallInteger('age_max_months'); // Edad máxima en meses
            $table->string('area'); // Área: MG (Motricidad Gruesa), MF (Motricidad Fina), AL (Área del Lenguaje), PS (Personal Social) - enum DevelopmentArea
            $table->text('description'); // Texto oficial del ítem

            // Índices para búsquedas eficientes
            $table->index(['area', 'age_min_months']); // Buscar ítems por área y edad mínima
            $table->index('age_max_months'); // Edad máxima
            $table->unique(['item_number', 'area']); // Un ítem único por número y área
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('development_items');
    }
};

