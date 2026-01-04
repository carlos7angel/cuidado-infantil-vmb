<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('child_development_evaluation_scores', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('child_development_evaluations')->cascadeOnDelete();
            $table->string('area'); // Área: MG (Motricidad Gruesa), MF (Motricidad Fina), AL (Área del Lenguaje), PS (Personal Social) - enum DevelopmentArea
            $table->unsignedSmallInteger('raw_score'); // Puntaje crudo (suma de ítems logrados en esa área)
            $table->string('status'); // Interpretación: alerta, medio, medio_alto, alto - enum DevelopmentStatus
            $table->timestamps();

            // Un puntaje por área por evaluación
            $table->unique(['evaluation_id', 'area'], 'dev_eval_scores_unique');

            // Índices para consultas frecuentes
            $table->index('evaluation_id'); // Cargar todos los puntajes de una evaluación
            $table->index(['area', 'status']); // Análisis por área y estado
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_development_evaluation_scores');
    }
};

