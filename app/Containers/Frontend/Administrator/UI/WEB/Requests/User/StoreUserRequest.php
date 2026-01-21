<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\User;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Ship\Parents\Requests\Request as ParentRequest;
use Illuminate\Validation\Rule;

final class StoreUserRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'user_role' => 'required|string|in:municipal_admin,childcare_admin',
            'send_email' => 'nullable|string|in:on',
        ];

        // If childcare_admin role is selected, childcare_center_id is required
        if ($this->input('user_role') === Role::CHILDCARE_ADMIN) {
            $rules['childcare_center_id'] = 'required|exists:childcare_centers,id';
        }

        return $rules;
    }

    public function authorize(): bool
    {
        return $this->user()->hasRole(Role::SUPER_ADMIN);
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'email.max' => 'El correo electrónico no puede exceder 255 caracteres.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'user_role.required' => 'Debe seleccionar un rol.',
            'user_role.in' => 'El rol seleccionado no es válido.',
            'childcare_center_id.required' => 'Debe seleccionar un centro de cuidado infantil para administradores de CCI.',
            'childcare_center_id.exists' => 'El centro de cuidado infantil seleccionado no existe.',
            'send_email.in' => 'El valor del envío de email no es válido.',
        ];
    }
}

