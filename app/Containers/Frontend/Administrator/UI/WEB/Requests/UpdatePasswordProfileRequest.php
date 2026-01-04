<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;
use Illuminate\Support\Facades\Auth;

final class UpdatePasswordProfileRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            ],
            'confirm_password' => [
                'required',
                'string',
                'same:password',
            ],
        ];
    }

    public function authorize(): bool
    {
        return Auth::guard('web')->check();
    }

    public function messages(): array
    {
        return [
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'password.regex' => 'La contraseña debe contener al menos una letra minúscula, una mayúscula, un número y un carácter especial.',
            'confirm_password.required' => 'La confirmación de contraseña es obligatoria.',
        ];
    }
}

