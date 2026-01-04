<?php

namespace App\Containers\Monitoring\Educator\UI\API\Requests;

use App\Containers\AppSection\User\Enums\Gender;
use App\Ship\Parents\Requests\Request as ParentRequest;
use Illuminate\Validation\Rule;

final class UpdateEducatorRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'gender' => ['nullable', Rule::enum(Gender::class)],
            'birth' => 'nullable|date|before:today',
            'state' => 'nullable|string|max:255',
            'dni' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.string' => 'El nombre debe ser un texto válido.',
            'first_name.max' => 'El nombre no puede exceder 255 caracteres.',
            'last_name.string' => 'El apellido debe ser un texto válido.',
            'last_name.max' => 'El apellido no puede exceder 255 caracteres.',
            'gender.enum' => 'El género seleccionado no es válido.',
            'birth.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'birth.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'state.string' => 'El departamento/estado debe ser un texto válido.',
            'state.max' => 'El departamento/estado no puede exceder 255 caracteres.',
            'dni.string' => 'El DNI debe ser un texto válido.',
            'dni.max' => 'El DNI no puede exceder 20 caracteres.',
            'phone.string' => 'El teléfono debe ser un texto válido.',
            'phone.max' => 'El teléfono no puede exceder 20 caracteres.',
        ];
    }
}
