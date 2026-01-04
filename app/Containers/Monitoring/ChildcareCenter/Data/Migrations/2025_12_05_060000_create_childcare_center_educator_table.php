<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('childcare_center_educator', static function (Blueprint $table) {
            $table->foreignId('childcare_center_id')->constrained()->cascadeOnDelete();
            $table->foreignId('educator_id')->constrained()->cascadeOnDelete();
            $table->dateTime('assigned_at');

            // Clave primaria compuesta
            $table->primary(['childcare_center_id', 'educator_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('childcare_center_educator');
    }
};
