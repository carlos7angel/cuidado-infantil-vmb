<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\User;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListAdminUsersJsonDataTableRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [

        ];
    }

    public function authorize(): bool
    {
        return $this->user()->hasRole(Role::SUPER_ADMIN);
    }
}

