<?php

namespace App\Containers\Monitoring\IncidentReport\UI\API\Requests;

use App\Containers\Monitoring\IncidentReport\Enums\IncidentSeverity;
use App\Containers\Monitoring\IncidentReport\Enums\IncidentType;
use App\Ship\Parents\Requests\Request as ParentRequest;

final class CreateIncidentReportRequest extends ParentRequest
{
    protected array $decode = [
        'child_id',
    ];

    public function rules(): array
    {
        return [
            'child_id' => 'required|exists:children,id',
            'type' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!IncidentType::tryFrom($value)) {
                    $fail('El tipo de incidente especificado no es válido.');
                }
            }],
            'severity_level' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!IncidentSeverity::tryFrom($value)) {
                    $fail('El nivel de gravedad especificado no es válido.');
                }
            }],
            'description' => 'required|string|min:3|max:5000',
            'incident_date' => 'required|date|before_or_equal:today',
            'incident_time' => 'nullable|date_format:H:i',
            'incident_location' => 'required|string|max:255',
            'people_involved' => 'required|string|max:1000',
            
            // Campos opcionales
            'witnesses' => 'nullable|string|max:1000',
            'has_evidence' => 'nullable|boolean',
            'evidence_description' => 'nullable|string|max:2000',
            // 'evidence_files' => 'nullable|array',
            // 'evidence_files.*' => 'file|mimes:jpeg,jpg,png,gif,webp|max:10240', // Máximo 10MB por archivo
            'actions_taken' => 'nullable|string|max:2000',
            'additional_comments' => 'nullable|string|max:2000',
            'childcare_center_id' => 'nullable|exists:childcare_centers,id',
            'room_id' => 'nullable|exists:rooms,id',
        ];
    }

    public function messages(): array
    {
        return [
            'child_id.required' => 'El ID del niño es obligatorio.',
            'child_id.exists' => 'El niño especificado no existe.',
            'type.required' => 'El tipo de incidente es obligatorio.',
            'severity_level.required' => 'El nivel de gravedad es obligatorio.',
            'description.required' => 'La descripción es obligatoria.',
            'description.min' => 'La descripción debe tener al menos 10 caracteres.',
            'description.max' => 'La descripción no puede exceder 5000 caracteres.',
            'incident_date.required' => 'La fecha del incidente es obligatoria.',
            'incident_date.date' => 'La fecha del incidente debe ser una fecha válida.',
            'incident_date.before_or_equal' => 'La fecha del incidente no puede ser futura.',
            'incident_time.date_format' => 'La hora del incidente debe tener el formato HH:mm.',
            'incident_location.required' => 'El lugar del incidente es obligatorio.',
            'people_involved.required' => 'Las personas involucradas es un campo obligatorio.',
            'evidence_files.array' => 'Los archivos de evidencia deben ser un array.',
            'evidence_files.*.file' => 'Cada archivo de evidencia debe ser un archivo válido.',
            'evidence_files.*.mimes' => 'Los archivos de evidencia deben ser imágenes (jpeg, jpg, png, gif, webp).',
            'evidence_files.*.max' => 'Cada archivo de evidencia no puede ser mayor a 10MB.',
        ];
    }
}
