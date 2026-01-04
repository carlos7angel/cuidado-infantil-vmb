<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('development_norms', static function (Blueprint $table) {
            $table->id();
            $table->string('area'); // Área: MG (Motricidad Gruesa), MF (Motricidad Fina), AL (Área del Lenguaje), PS (Personal Social) - enum DevelopmentArea
            $table->unsignedSmallInteger('age_min_months'); // Edad mínima en meses
            $table->unsignedSmallInteger('age_max_months'); // Edad máxima en meses
            $table->unsignedSmallInteger('min_score'); // Puntaje mínimo del rango
            $table->unsignedSmallInteger('max_score'); // Puntaje máximo del rango
            $table->string('status'); // Interpretación: alerta, medio, medio_alto, alto - enum DevelopmentStatus

            // Índices para búsquedas eficientes
            $table->index(['area', 'age_min_months', 'age_max_months']); // Buscar normas por área y rango de edad
            $table->index(['min_score', 'max_score']); // Búsqueda por rango de puntaje
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('development_norms');
    }
};

