<?php

namespace App\Containers\AppSection\Authentication\UI\API\Requests\PasswordReset;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ForgotPasswordRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe proporcionar un correo electrónico válido.',
        ];
    }
}
