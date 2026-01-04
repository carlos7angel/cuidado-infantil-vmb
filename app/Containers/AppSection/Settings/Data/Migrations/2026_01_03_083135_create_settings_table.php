<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('settings', static function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->nullable()->comment('Tipo de dato: string, integer, boolean, json, etc.');
            $table->string('group')->nullable()->comment('Grupo/Categoría para organizar settings');
            $table->text('description')->nullable()->comment('Descripción del setting');
            $table->timestamps();

            $table->index('group');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
