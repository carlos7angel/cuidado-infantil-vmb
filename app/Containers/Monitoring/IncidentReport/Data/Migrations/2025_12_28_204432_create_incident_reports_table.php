<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('incident_reports', static function (Blueprint $table) {
            $table->id();
            
            // Código único para seguimiento (generado automáticamente)
            $table->string('code', 20)->unique()->comment('Código único para seguimiento del incidente');
            
            // Estado del reporte (enum: reportado, en_revision, cerrado, escalado, archivado)
            $table->string('status')->default('reportado')->comment('Estado: reportado, en_revision, cerrado, escalado, archivado');
            
            // Relación con el niño
            $table->foreignId('child_id')->constrained()->cascadeOnDelete()->comment('Niño involucrado en el incidente');
            
            // Tipo de incidente (enum: accidente, conducta_inapropiada, lesion_fisica, negligencia, maltrato_psicologico, maltrato_fisico, otro)
            $table->string('type')->comment('Tipo: accidente, conducta_inapropiada, lesion_fisica, negligencia, maltrato_psicologico, maltrato_fisico, otro');
            
            // Nivel de gravedad (enum: leve, moderado, grave, critico)
            $table->string('severity_level')->comment('Nivel de gravedad: leve, moderado, grave, critico');
            
            // Descripción del incidente
            $table->text('description')->comment('Descripción detallada del incidente');
            
            // Fecha y hora del incidente
            $table->date('incident_date')->comment('Fecha en que ocurrió el incidente');
            $table->time('incident_time')->nullable()->comment('Hora en que ocurrió el incidente');
            
            // Lugar del incidente
            $table->string('incident_location')->nullable()->comment('Lugar donde ocurrió el incidente');
            
            // Personas involucradas y testigos
            $table->text('people_involved')->nullable()->comment('Personas involucradas en el incidente');
            $table->text('witnesses')->nullable()->comment('Testigos del incidente');
            
            // Quien reportó
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete()->comment('Usuario que reportó el incidente');
            $table->timestamp('reported_at')->nullable()->comment('Fecha y hora en que se reportó');
            
            // Evidencia
            $table->boolean('has_evidence')->default(false)->comment('Indica si existe evidencia del incidente');
            $table->text('evidence_description')->nullable()->comment('Descripción de la evidencia');
            $table->json('evidence_file_ids')->nullable()->comment('IDs de archivos de evidencia (fotos, documentos, etc.)');
            
            // Acciones tomadas
            $table->text('actions_taken')->nullable()->comment('Acciones tomadas en respuesta al incidente');
            
            // Comentarios adicionales
            $table->text('additional_comments')->nullable()->comment('Comentarios adicionales u observaciones');
            
            // Fecha de cierre
            $table->date('closed_date')->nullable()->comment('Fecha en que se cerró el incidente');
            
            // Campos adicionales importantes para seguimiento
            $table->foreignId('childcare_center_id')->nullable()->constrained()->nullOnDelete()->comment('Centro de cuidado donde ocurrió (si aplica)');
            $table->foreignId('room_id')->nullable()->constrained()->nullOnDelete()->comment('Sala donde ocurrió (si aplica)');
            
            // Evaluación y seguimiento
            $table->foreignId('evaluated_by')->nullable()->constrained('users')->nullOnDelete()->comment('Usuario que evaluó/revisó el incidente');
            $table->date('evaluation_date')->nullable()->comment('Fecha en que se evaluó el incidente');
            $table->text('evaluation_result')->nullable()->comment('Resultado de la evaluación');
            
            // Escalamiento
            $table->date('escalated_date')->nullable()->comment('Fecha en que se escaló el incidente');
            $table->string('escalated_to')->nullable()->comment('A quién se escaló (nombre o cargo)');
            $table->text('escalation_reason')->nullable()->comment('Razón del escalamiento');
            
            // Notificación a autoridades
            $table->boolean('requires_authority_notification')->default(false)->comment('Indica si requiere notificación a autoridades');
            $table->boolean('notified_to_authorities')->default(false)->comment('Indica si ya se notificó a autoridades');
            $table->date('authority_notification_date')->nullable()->comment('Fecha de notificación a autoridades');
            $table->text('authority_notification_details')->nullable()->comment('Detalles de la notificación a autoridades');
            
            // Seguimiento
            $table->boolean('follow_up_required')->default(false)->comment('Indica si se requiere seguimiento');
            $table->date('next_follow_up_date')->nullable()->comment('Fecha del próximo seguimiento');
            $table->text('follow_up_notes')->nullable()->comment('Notas de seguimiento');
            
            // Medidas preventivas
            $table->text('preventive_measures')->nullable()->comment('Medidas preventivas implementadas');
            
            // Relación con otros incidentes
            $table->json('related_incident_ids')->nullable()->comment('IDs de incidentes relacionados');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('code');
            $table->index('status');
            $table->index('child_id');
            $table->index('type');
            $table->index('severity_level');
            $table->index('incident_date');
            $table->index('reported_by');
            $table->index('childcare_center_id');
            $table->index(['status', 'severity_level']);
            $table->index(['child_id', 'incident_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_reports');
    }
};
