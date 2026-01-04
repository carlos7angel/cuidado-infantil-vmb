<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('child_development_evaluation_items', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('child_development_evaluations')->cascadeOnDelete();
            $table->foreignId('development_item_id')->constrained('development_items')->cascadeOnDelete();
            $table->boolean('achieved'); // Si el ítem fue logrado (true) o no (false)
            $table->timestamps();

            // Un resultado por ítem por evaluación
            $table->unique(['evaluation_id', 'development_item_id'], 'dev_eval_items_unique');

            // Índices para consultas frecuentes
            $table->index('evaluation_id'); // Cargar todos los ítems de una evaluación
            $table->index('development_item_id'); // Análisis por ítem específico
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_development_evaluation_items');
    }
};

