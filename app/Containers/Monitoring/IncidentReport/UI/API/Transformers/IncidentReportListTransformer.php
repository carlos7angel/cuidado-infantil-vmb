<?php

namespace App\Containers\Monitoring\IncidentReport\UI\API\Transformers;

use App\Containers\Monitoring\IncidentReport\Models\IncidentReport;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

/**
 * Transformer simplificado para listado de reportes de incidentes.
 * Solo incluye información esencial ya guardada, sin procesamiento pesado.
 */
final class IncidentReportListTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(IncidentReport $incidentReport): array
    {
        return [
            'type' => $incidentReport->getResourceKey(),
            'id' => $incidentReport->getHashedKey(),
            'code' => $incidentReport->code,
            
            // Estado y tipo
            'status' => $incidentReport->status->value,
            'status_label' => $incidentReport->status->label(),
            'type' => $incidentReport->type->value,
            'type_label' => $incidentReport->type->label(),
            'severity_level' => $incidentReport->severity_level?->value,
            'severity_label' => $incidentReport->severity_level?->label() ?? 'Sin registro',
            'severity_color' => $incidentReport->severity_level?->color() ?? '#9E9E9E',
            
            // Información básica del incidente
            'description' => $incidentReport->description,
            'incident_date' => $incidentReport->incident_date->format('Y-m-d'),
            'incident_date_readable' => $incidentReport->incident_date->format('d/m/Y'),
            'incident_time' => $incidentReport->incident_time,
            'incident_location' => $incidentReport->incident_location,
            
            // Información del niño (solo básica)
            'child_id' => $incidentReport->child_id && $incidentReport->relationLoaded('child') && $incidentReport->child 
                ? $incidentReport->child->getHashedKey() 
                : null,
            'child_name' => $incidentReport->relationLoaded('child') && $incidentReport->child 
                ? $incidentReport->child->full_name 
                : null,

            'room_id' => $incidentReport->room_id && $incidentReport->relationLoaded('room') && $incidentReport->room 
                ? $incidentReport->room->getHashedKey() 
                : null,
            'room_name' => $incidentReport->relationLoaded('room') && $incidentReport->room 
                ? $incidentReport->room->name 
                : null,
            
            // Evidencia
            'has_evidence' => $incidentReport->has_evidence,
            'evidence_files_count' => !empty($incidentReport->evidence_file_ids) ? count($incidentReport->evidence_file_ids) : 0,
            
            // Indicadores
            'requires_immediate_attention' => $incidentReport->requiresImmediateAttention(),
            'is_active' => $incidentReport->isActive(),
            
            // Fechas
            'reported_at' => $incidentReport->reported_at?->format('Y-m-d H:i:s'),
            'reported_at_readable' => $incidentReport->formatted_reported_at,

            // Escalamiento
            'escalated_to' => $incidentReport->escalated_to,

            // Metadatos
            'created_at' => $incidentReport->created_at->format('Y-m-d H:i:s'),
            'readable_created_at' => $incidentReport->created_at->diffForHumans(),
        ];
    }
}

