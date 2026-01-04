<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('who_bmi_for_age_length', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('month')->comment('Edad en meses (0-24 meses, 0-2 años) - IMC usando longitud (medición acostado)');
            $table->unsignedTinyInteger('gender')->comment('1 = Masculino, 2 = Femenino');
            
            // Parámetros LMS (Lambda-Mu-Sigma)
            $table->decimal('L', 8, 5)->comment('Lambda');
            $table->decimal('M', 8, 5)->comment('Mu (Mediana)');
            $table->decimal('S', 8, 5)->comment('Sigma');
            
            // Desviaciones estándar precalculadas
            $table->decimal('SD3neg', 8, 2)->comment('SD -3');
            $table->decimal('SD2neg', 8, 2)->comment('SD -2');
            $table->decimal('SD1neg', 8, 2)->comment('SD -1');
            $table->decimal('SD0', 8, 2)->comment('SD 0 (Mediana)');
            $table->decimal('SD1', 8, 2)->comment('SD +1');
            $table->decimal('SD2', 8, 2)->comment('SD +2');
            $table->decimal('SD3', 8, 2)->comment('SD +3');
            
            // Índices para búsquedas rápidas
            $table->index(['month', 'gender']);
            $table->unique(['month', 'gender'], 'who_bmi_age_length_month_gender_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('who_bmi_for_age_length');
    }
};
