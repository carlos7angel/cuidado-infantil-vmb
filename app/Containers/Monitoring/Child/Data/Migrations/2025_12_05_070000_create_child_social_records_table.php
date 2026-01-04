<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('child_social_records', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->unique()->constrained()->cascadeOnDelete();

            // Infante a cargo de
            $table->string('guardian_type'); // enum GuardianType

            // =================================================================
            // EGRESOS
            // =================================================================
            $table->decimal('expense_food', 10, 2)->nullable();
            $table->decimal('expense_education', 10, 2)->nullable();
            $table->decimal('expense_housing', 10, 2)->nullable();
            $table->decimal('expense_transport', 10, 2)->nullable();
            $table->decimal('expense_clothing', 10, 2)->nullable();
            $table->decimal('expense_utilities', 10, 2)->nullable();
            $table->decimal('expense_health', 10, 2)->nullable();
            $table->decimal('expense_debts', 10, 2)->nullable();
            $table->text('expense_debts_detail')->nullable();

            // =================================================================
            // SITUACIÓN DE HABITABILIDAD
            // =================================================================
            $table->string('housing_type')->nullable(); // enum HousingType
            $table->string('housing_tenure')->nullable(); // enum HousingTenure
            $table->string('housing_wall_material')->nullable(); // madera, ladrillo, adobe
            $table->string('housing_floor_material')->nullable(); // tierra, cemento, machimbre, parquet
            $table->string('housing_finish')->nullable(); // obra fina, obra gruesa
            $table->unsignedTinyInteger('housing_bedrooms')->nullable();
            $table->json('housing_rooms')->nullable(); // ['dormitorio', 'sala', 'cocina', 'comedor', 'baño']
            $table->json('housing_utilities')->nullable(); // ['agua_potable', 'energia_electrica', etc.]

            // Transporte
            $table->string('transport_type')->nullable(); // enum TransportType
            $table->string('travel_time')->nullable(); // enum TravelTime

            // Croquis
            $table->string('home_sketch')->nullable(); // ruta imagen
            $table->string('work_sketch')->nullable(); // ruta imagen

            // Historia y valoración
            $table->text('family_history')->nullable();
            $table->text('professional_assessment')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_social_records');
    }
};
