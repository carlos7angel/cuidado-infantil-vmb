<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('child_medical_records', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->unique()->constrained()->cascadeOnDelete();

            // Seguro médico
            $table->boolean('has_insurance')->default(false);
            $table->string('insurance_details')->nullable();

            // Datos físicos
            $table->decimal('weight', 5, 2)->nullable(); // kg
            $table->decimal('height', 5, 2)->nullable(); // cm

            // Alergias
            $table->boolean('has_allergies')->default(false);
            $table->text('allergies_details')->nullable();

            // Tratamiento médico
            $table->boolean('has_medical_treatment')->default(false);
            $table->text('medical_treatment_details')->nullable();

            // Tratamiento psicológico
            $table->boolean('has_psychological_treatment')->default(false);
            $table->text('psychological_treatment_details')->nullable();

            // Déficits
            $table->boolean('has_deficit')->default(false);
            $table->string('deficit_auditory')->nullable(); // enum DeficitLevel
            $table->string('deficit_visual')->nullable(); // enum DeficitLevel
            $table->string('deficit_tactile')->nullable(); // enum DeficitLevel
            $table->string('deficit_motor')->nullable(); // enum DeficitLevel

            // Enfermedad
            $table->boolean('has_illness')->default(false);
            $table->text('illness_details')->nullable();

            // Documentos
            $table->string('medical_report_document')->nullable();
            $table->string('diagnosis_document')->nullable();

            // Otros
            $table->text('other_observations')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_medical_records');
    }
};
