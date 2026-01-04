<?php

namespace App\Containers\Monitoring\ChildDevelopment\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class GetApplicableDevelopmentItemsRequest extends ParentRequest
{
    protected array $decode = [
        'child_id', // Decodificar el ID hasheado del niño desde la URL
    ];

    public function rules(): array
    {
        return [
            'evaluation_date' => 'nullable|date|before_or_equal:today',
        ];
    }

    public function messages(): array
    {
        return [
            'evaluation_date.date' => 'La fecha de evaluación debe ser una fecha válida.',
            'evaluation_date.before_or_equal' => 'La fecha de evaluación no puede ser futura.',
        ];
    }
}

