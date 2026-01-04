<?php

namespace App\Containers\Monitoring\ChildVaccination\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class DeleteChildVaccinationRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [
            'id' => 'exists:child_vaccinations,id',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'El ID del registro de vacunación es requerido.',
            'id.exists' => 'El registro de vacunación especificado no existe.',
        ];
    }
}
