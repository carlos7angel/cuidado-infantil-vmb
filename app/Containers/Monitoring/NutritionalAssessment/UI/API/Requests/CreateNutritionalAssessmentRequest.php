<?php

namespace App\Containers\Monitoring\NutritionalAssessment\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CreateNutritionalAssessmentRequest extends ParentRequest
{
    protected array $decode = [
        'child_id', // Decodificar el ID hasheado del niño desde la URL
    ];

    public function rules(): array
    {
        return [
            'weight' => 'required|numeric|min:0.1|max:100',
            'height' => 'required|numeric|min:30|max:200',
            'head_circumference' => 'nullable|numeric|min:20|max:70',
            'arm_circumference' => 'nullable|numeric|min:5|max:30',
            'assessment_date' => 'nullable|date|before_or_equal:today',
            'observations' => 'nullable|string|max:1000',
            'recommendations' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'weight.required' => 'El peso es requerido.',
            'weight.numeric' => 'El peso debe ser un número.',
            'weight.min' => 'El peso debe ser mayor a 0.1 kg.',
            'weight.max' => 'El peso debe ser menor a 100 kg.',
            'height.required' => 'La talla/longitud es requerida.',
            'height.numeric' => 'La talla/longitud debe ser un número.',
            'height.min' => 'La talla/longitud debe ser mayor a 30 cm.',
            'height.max' => 'La talla/longitud debe ser menor a 200 cm.',
            'head_circumference.numeric' => 'El perímetro cefálico debe ser un número.',
            'arm_circumference.numeric' => 'El perímetro braquial debe ser un número.',
            'assessment_date.date' => 'La fecha de valoración debe ser una fecha válida.',
            'assessment_date.before_or_equal' => 'La fecha de valoración no puede ser futura.',
        ];
    }
}
