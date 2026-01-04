<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\User;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ShowUserRequest extends ParentRequest
{
    protected array $decode = [
        // 'id',
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

