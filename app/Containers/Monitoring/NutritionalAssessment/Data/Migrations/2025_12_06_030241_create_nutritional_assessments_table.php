<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('nutritional_assessments', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assessed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('assessment_date');
            $table->unsignedSmallInteger('age_in_months'); // Edad al momento de la valoración

            // Mediciones antropométricas
            $table->decimal('weight', 5, 2);              // Peso en kg (ej: 12.50)
            $table->decimal('height', 5, 2);              // Talla/Longitud en cm (ej: 85.50)
                                                           // Nota: 0-24 meses = longitud (acostado), 24-60 meses = talla (de pie)
            $table->decimal('head_circumference', 4, 2)->nullable(); // Perímetro cefálico cm
            $table->decimal('arm_circumference', 4, 2)->nullable();  // Perímetro braquial cm

            // Indicadores Z-score (desviaciones estándar según OMS)
            $table->decimal('z_weight_age', 4, 2)->nullable();    // Peso/Edad
            $table->decimal('z_height_age', 4, 2)->nullable();    // Talla/Edad
            $table->decimal('z_weight_height', 4, 2)->nullable(); // Peso/Talla
            $table->decimal('z_bmi_age', 4, 2)->nullable();       // IMC/Edad

            // Clasificación nutricional
            $table->string('status_weight_age')->nullable();    // Clasificación P/E
            $table->string('status_height_age')->nullable();    // Clasificación T/E
            $table->string('status_weight_height')->nullable(); // Clasificación P/T
            $table->string('status_bmi_age')->nullable();       // Clasificación IMC/E

            // Observaciones y seguimiento
            $table->text('observations')->nullable();
            $table->text('recommendations')->nullable();
            $table->date('next_assessment_date')->nullable();

            $table->timestamps();

            // Índice para consultas frecuentes
            $table->index(['child_id', 'assessment_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nutritional_assessments');
    }
};
