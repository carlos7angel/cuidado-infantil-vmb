<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('attendances', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained()->cascadeOnDelete();
            $table->foreignId('childcare_center_id')->constrained()->cascadeOnDelete();
            $table->foreignId('registered_by')->nullable()->constrained('users')->nullOnDelete();

            // Fecha y horarios
            $table->date('date');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();

            // Estado de asistencia
            $table->string('status'); // enum AttendanceStatus

            // Observaciones
            $table->text('observations')->nullable();

            $table->timestamps();

            // Un niño solo puede tener un registro de asistencia por día por centro
            $table->unique(['child_id', 'childcare_center_id', 'date'], 'attendance_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
