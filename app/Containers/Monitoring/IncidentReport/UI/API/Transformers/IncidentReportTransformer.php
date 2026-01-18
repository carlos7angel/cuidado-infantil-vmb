<?php

namespace App\Containers\Monitoring\IncidentReport\UI\API\Transformers;

use App\Containers\Monitoring\IncidentReport\Models\IncidentReport;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class IncidentReportTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [
        'child',
        'evidence_files'
    ];

    protected array $availableIncludes = [
        'child', 
        'reported_by', 
        'childcare_center', 
        'room',
        'evidence_files',
        'files'
    ];

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
            
            // Información del incidente
            'description' => $incidentReport->description,
            'incident_date' => $incidentReport->incident_date->format('Y-m-d'),
            'incident_date_readable' => $incidentReport->incident_date->format('d/m/Y'),
            'incident_time' => $incidentReport->incident_time,
            'incident_location' => $incidentReport->incident_location,
            'incident_date_time_readable' => $incidentReport->formatted_incident_date_time,
            
            // Personas involucradas
            'people_involved' => $incidentReport->people_involved,
            'witnesses' => $incidentReport->witnesses,
            
            // Evidencia
            'has_evidence' => $incidentReport->has_evidence,
            'evidence_description' => $incidentReport->evidence_description,
            'evidence_file_ids' => $incidentReport->evidence_file_ids ?? [],
            'evidence_files_count' => !empty($incidentReport->evidence_file_ids) ? count($incidentReport->evidence_file_ids) : 0,
            
            // Acciones y comentarios
            'actions_taken' => $incidentReport->actions_taken,
            'additional_comments' => $incidentReport->additional_comments,
            'follow_up_notes' => $incidentReport->follow_up_notes,
            'escalated_to' => $incidentReport->escalated_to,
            
            // Fechas
            'reported_at' => $incidentReport->reported_at?->format('Y-m-d H:i:s'),
            'reported_at_readable' => $incidentReport->formatted_reported_at,
            'closed_date' => $incidentReport->closed_date?->format('Y-m-d'),
            
            // Indicadores
            'requires_immediate_attention' => $incidentReport->requiresImmediateAttention(),
            'requires_authority_notification' => $incidentReport->requiresAuthorityNotification(),
            'is_active' => $incidentReport->isActive(),

            // Detalles de notificación a autoridades
            'authority_notification_details' => $incidentReport->authority_notification_details,

            // Metadatos
            'created_at' => $incidentReport->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $incidentReport->updated_at->format('Y-m-d H:i:s'),
            'readable_created_at' => $incidentReport->created_at->diffForHumans(),
            'readable_updated_at' => $incidentReport->updated_at->diffForHumans(),
        ];
    }

    /**
     * Include child relationship.
     */
    public function includeChild(IncidentReport $incidentReport)
    {
        if (!$incidentReport->relationLoaded('child') || !$incidentReport->child) {
            return null;
        }

        return $this->item($incidentReport->child, new \App\Containers\Monitoring\Child\UI\API\Transformers\ChildTransformer());
    }

    /**
     * Include reported by user relationship.
     */
    public function includeReportedBy(IncidentReport $incidentReport)
    {
        if (!$incidentReport->relationLoaded('reportedBy') || !$incidentReport->reportedBy) {
            return null;
        }

        return $this->item($incidentReport->reportedBy, new \App\Containers\AppSection\User\UI\API\Transformers\UserTransformer());
    }

    /**
     * Include childcare center relationship.
     */
    public function includeChildcareCenter(IncidentReport $incidentReport)
    {
        if (!$incidentReport->relationLoaded('childcareCenter') || !$incidentReport->childcareCenter) {
            return null;
        }

        return $this->item($incidentReport->childcareCenter, new \App\Containers\Monitoring\ChildcareCenter\UI\API\Transformers\ChildcareCenterTransformer());
    }

    /**
     * Include room relationship.
     */
    public function includeRoom(IncidentReport $incidentReport)
    {
        if (!$incidentReport->relationLoaded('room') || !$incidentReport->room) {
            return null;
        }

        return $this->item($incidentReport->room, new \App\Containers\Monitoring\Room\UI\API\Transformers\RoomTransformer());
    }

    /**
     * Include evidence files (based on evidence_file_ids).
     */
    public function includeEvidenceFiles(IncidentReport $incidentReport)
    {
        $files = $incidentReport->evidenceFiles();

        if ($files->isEmpty()) {
            return $this->null();
        }

        return $this->collection($files, function ($file) {
            return [
                'type' => 'file',
                'id' => $file->getHashedKey(),
                'unique_code' => $file->unique_code,
                'name' => $file->name,
                'original_name' => $file->original_name,
                'description' => $file->description,
                'mime_type' => $file->mime_type,
                'size' => $file->size,
                'human_readable_size' => $file->human_readable_size,
                'url' => $file->url,
                'path' => $file->path,
                'extension' => $file->extension,
                'is_image' => $file->is_image,
                'is_document' => $file->is_document,
                'type_category' => $file->getTypeCategory(),
                'created_at' => $file->created_at?->format('Y-m-d H:i:s'),
                'updated_at' => $file->updated_at?->format('Y-m-d H:i:s'),
            ];
        });
    }

    /**
     * Include all files associated with this incident report (morphMany relationship).
     */
    public function includeFiles(IncidentReport $incidentReport)
    {
        if (!$incidentReport->relationLoaded('files')) {
            return null;
        }

        $files = $incidentReport->files;

        if ($files->isEmpty()) {
            return $this->null();
        }

        return $this->collection($files, function ($file) {
            return [
                'type' => 'file',
                'id' => $file->getHashedKey(),
                'unique_code' => $file->unique_code,
                'name' => $file->name,
                'original_name' => $file->original_name,
                'description' => $file->description,
                'mime_type' => $file->mime_type,
                'size' => $file->size,
                'human_readable_size' => $file->human_readable_size,
                'url' => $file->url,
                'path' => $file->path,
                'extension' => $file->extension,
                'is_image' => $file->is_image,
                'is_document' => $file->is_document,
                'type_category' => $file->getTypeCategory(),
                'created_at' => $file->created_at?->format('Y-m-d H:i:s'),
                'updated_at' => $file->updated_at?->format('Y-m-d H:i:s'),
            ];
        });
    }
}
