<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\User;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class EditUserRequest extends ParentRequest
{
    protected array $decode = [
        // 'user_id',
    ];

    public function rules(): array
    {
        return [
            'user_id' => 'exists:users,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
