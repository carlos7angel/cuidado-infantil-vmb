<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('vaccine_doses', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('vaccine_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('dose_number');
            $table->unsignedSmallInteger('recommended_age_months'); // Edad ideal
            $table->unsignedSmallInteger('min_age_months');         // Edad mínima permitida
            $table->unsignedSmallInteger('max_age_months')->nullable(); // Edad máxima (null = sin límite)
            $table->string('description')->nullable();
            $table->timestamps();

            $table->unique(['vaccine_id', 'dose_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vaccine_doses');
    }
};
