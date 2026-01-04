<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('children', static function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('paternal_last_name');
            $table->string('maternal_last_name');
            $table->string('gender');
            $table->string('avatar')->nullable();
            $table->date('birth_date');
            $table->string('language')->nullable();
            $table->string('country')->nullable();
            $table->string('state');
            $table->string('city');
            $table->string('address');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
