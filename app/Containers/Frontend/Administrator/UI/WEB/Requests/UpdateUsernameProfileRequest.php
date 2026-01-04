<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

final class UpdateUsernameProfileRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        $userId = Auth::guard('web')->id();

        return [
            'username' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('users', 'name')->ignore($userId),
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
            'username.required' => 'El nombre de usuario es obligatorio.',
            'username.min' => 'El nombre de usuario debe tener al menos 3 caracteres.',
            'username.max' => 'El nombre de usuario no puede exceder 255 caracteres.',
            'username.unique' => 'Este nombre de usuario ya está en uso.',
        ];
    }
}

