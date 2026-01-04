<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\User;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateUserPasswordRequest extends ParentRequest
{
    protected array $decode = [
        // 'user_id',
    ];

    public function rules(): array
    {
        return [
            'user_id' => 'exists:users,id',
            'new_password' => 'required|string|min:8',
            'confirm_password' => 'required|string|same:new_password',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
