<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('child_enrollments', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained()->cascadeOnDelete();
            $table->foreignId('childcare_center_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->nullable()->constrained()->nullOnDelete();

            // Datos de inscripción
            $table->date('enrollment_date');
            $table->date('withdrawal_date')->nullable();
            $table->string('status')->nullable(); // enum EnrollmentStatus

            // =================================================================
            // DOCUMENTOS
            // =================================================================
            $table->string('file_admission_request')->nullable(); // Carta de solicitud de admisión
            $table->string('file_commitment')->nullable(); // Compromiso
            $table->string('file_birth_certificate')->nullable(); // Certificado de nacimiento
            $table->string('file_vaccination_card')->nullable(); // Carnet de vacunas
            $table->string('file_parent_id')->nullable(); // Documento identidad padre/madre
            $table->string('file_work_certificate')->nullable(); // Certificado constancia laboral
            $table->string('file_utility_bill')->nullable(); // Recibo de agua y luz
            $table->string('file_home_sketch')->nullable(); // Croquis domicilio actual
            $table->string('file_residence_certificate')->nullable(); // Constancia de vivienda
            $table->string('file_pickup_authorization')->nullable(); // Autorización de recojo

            $table->text('observations')->nullable();
            $table->timestamps();

            // Un niño solo puede estar inscrito una vez activamente en un centro
            $table->unique(['child_id', 'childcare_center_id', 'enrollment_date'], 'enrollment_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_enrollments');
    }
};
