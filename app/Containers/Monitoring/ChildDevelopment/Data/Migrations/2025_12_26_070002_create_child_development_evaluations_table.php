<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('child_development_evaluations', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assessed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('evaluation_date');
            $table->unsignedSmallInteger('age_months'); // Edad en meses al momento de evaluación

            // Mediciones antropométricas (independientes de evaluación nutricional)
            $table->decimal('weight', 5, 2)->nullable(); // Peso en kg
            $table->decimal('height', 5, 2)->nullable(); // Altura/Talla en cm

            // Observaciones y seguimiento
            $table->text('notes')->nullable(); // Observaciones generales
            $table->date('next_evaluation_date')->nullable(); // Próxima evaluación recomendada

            $table->timestamps();

            // Índices para consultas frecuentes
            $table->index(['child_id', 'evaluation_date']); // Consultas por niño y fecha
            $table->index('evaluation_date'); // Consultas por fecha
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_development_evaluations');
    }
};

