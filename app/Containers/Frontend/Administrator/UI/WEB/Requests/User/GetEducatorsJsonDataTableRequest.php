<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Educator;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListUsersRequest extends ParentRequest
{
    protected array $access = [
        'permissions' => '',
        'roles' => '',
    ];

    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        return true;
    }
}

