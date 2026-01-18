<?php

namespace App\Containers\Monitoring\IncidentReport\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateIncidentReportRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [
            // Campos que se pueden editar
            'description' => 'sometimes|string|min:3|max:5000',
            'actions_taken' => 'sometimes|nullable|string|max:2000',
            'additional_comments' => 'sometimes|nullable|string|max:2000',
            'follow_up_notes' => 'sometimes|nullable|string|max:2000',
            'authority_notification_details' => 'sometimes|nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => 'La descripción es obligatoria.',
            'description.min' => 'La descripción debe tener al menos 10 caracteres.',
            'description.max' => 'La descripción no puede exceder 5000 caracteres.',
            'actions_taken.max' => 'Las acciones tomadas no pueden exceder 2000 caracteres.',
            'additional_comments.max' => 'Los comentarios adicionales no pueden exceder 2000 caracteres.',
            'follow_up_notes.max' => 'Las notas de seguimiento no pueden exceder 2000 caracteres.',
            'authority_notification_details.max' => 'Los detalles de notificación a autoridades no pueden exceder 2000 caracteres.',
        ];
    }
}
