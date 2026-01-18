<?php

namespace App\Containers\Monitoring\ChildDevelopment\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CreateChildDevelopmentRequest extends ParentRequest
{
    protected array $decode = [
        'child_id', // Decodificar el ID hasheado del niño desde la URL
        'items.*',  // Decodificar los IDs hasheados de los items
    ];

    public function rules(): array
    {
        // child_id puede venir de la ruta o del body
        $childIdRule = $this->route('child_id') 
            ? 'nullable|exists:children,id' 
            : 'required|exists:children,id';

        return [
            'child_id' => $childIdRule,
            'evaluation_date' => 'nullable|date|before_or_equal:today',
            'age_months' => 'nullable|integer|min:0|max:96',
            'weight' => 'nullable|numeric|min:0.1|max:100',
            'height' => 'nullable|numeric|min:30|max:200',
            'notes' => 'nullable|string|max:2000',
            'actions_taken' => 'nullable|string|max:2000',
            'next_evaluation_date' => 'nullable|date|after:today',
            
            // Items logrados (solo se envían los que fueron logrados)
            // El backend reconstruirá el historial completo al mostrar la evaluación
            'items' => 'required|array|min:1',
            'items.*' => 'required|exists:development_items,id',
        ];
    }

    public function messages(): array
    {
        return [
            'child_id.required' => 'El ID del niño es requerido.',
            'child_id.exists' => 'El niño especificado no existe.',
            'evaluation_date.date' => 'La fecha de evaluación debe ser una fecha válida.',
            'evaluation_date.before_or_equal' => 'La fecha de evaluación no puede ser futura.',
            'age_months.integer' => 'La edad en meses debe ser un número entero.',
            'age_months.min' => 'La edad en meses debe ser mayor o igual a 0.',
            'age_months.max' => 'La edad en meses debe ser menor o igual a 96.',
            'weight.numeric' => 'El peso debe ser un número.',
            'weight.min' => 'El peso debe ser mayor a 0.1 kg.',
            'weight.max' => 'El peso debe ser menor a 100 kg.',
            'height.numeric' => 'La altura debe ser un número.',
            'height.min' => 'La altura debe ser mayor a 30 cm.',
            'height.max' => 'La altura debe ser menor a 200 cm.',
            'notes.max' => 'Las notas no pueden exceder 2000 caracteres.',
            'next_evaluation_date.date' => 'La próxima fecha de evaluación debe ser una fecha válida.',
            'next_evaluation_date.after' => 'La próxima fecha de evaluación debe ser futura.',
            
            'items.required' => 'Debe proporcionar al menos un ítem logrado.',
            'items.array' => 'Los items deben ser un array de IDs.',
            'items.min' => 'Debe proporcionar al menos un ítem logrado.',
            'items.*.required' => 'Cada elemento del array debe ser un ID de ítem válido.',
            'items.*.exists' => 'Uno o más ítems especificados no existen.',
        ];
    }
}
