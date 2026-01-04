<?php

namespace App\Containers\Monitoring\ChildVaccination\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CreateChildVaccinationRequest extends ParentRequest
{
    protected array $decode = [
        'child_id',
        'vaccine_dose_id',
    ];

    public function rules(): array
    {
        // child_id puede venir de la ruta o del body
        $childIdRule = $this->route('child_id') 
            ? 'nullable|exists:children,id' 
            : 'required|exists:children,id';

        return [
            'child_id' => $childIdRule,
            'vaccine_dose_id' => 'required|exists:vaccine_doses,id',
            'date_applied' => 'required|date|before_or_equal:today',
            'applied_at' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'child_id.required' => 'El ID del niño es requerido.',
            'child_id.exists' => 'El niño especificado no existe.',
            'vaccine_dose_id.required' => 'El ID de la dosis de vacuna es requerido.',
            'vaccine_dose_id.exists' => 'La dosis de vacuna especificada no existe.',
            'date_applied.required' => 'La fecha de aplicación es requerida.',
            'date_applied.date' => 'La fecha de aplicación debe ser una fecha válida.',
            'date_applied.before_or_equal' => 'La fecha de aplicación no puede ser futura.',
            'applied_at.max' => 'El lugar de aplicación no puede exceder 255 caracteres.',
            'notes.max' => 'Las notas no pueden exceder 1000 caracteres.',
        ];
    }
}
