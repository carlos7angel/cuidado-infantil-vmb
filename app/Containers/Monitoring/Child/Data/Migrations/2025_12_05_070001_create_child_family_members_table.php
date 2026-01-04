<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('child_family_members', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_social_record_id')->constrained()->cascadeOnDelete();

            // Datos personales
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date')->nullable();
            $table->string('gender')->nullable(); // enum Gender
            $table->string('kinship')->nullable(); // enum Kinship (parentesco)
            $table->string('education_level')->nullable(); // Grado de instrucción
            $table->string('profession')->nullable(); // Profesión/Ocupación
            $table->string('marital_status')->nullable(); // Estado civil
            $table->string('phone')->nullable();

            // Información laboral/ingresos
            $table->boolean('has_income')->default(false); // ¿Trabaja?
            $table->string('workplace')->nullable();
            $table->string('income_type')->nullable(); // enum IncomeType
            $table->decimal('total_income', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_family_members');
    }
};
