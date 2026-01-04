<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('child_vaccinations', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vaccine_dose_id')->constrained()->cascadeOnDelete();
            $table->date('date_applied');
            $table->string('applied_at')->nullable(); // Lugar donde se aplicó
            $table->text('notes')->nullable();
            $table->timestamps();

            // Un niño solo puede tener una vez cada dosis
            $table->unique(['child_id', 'vaccine_dose_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_vaccinations');
    }
};
