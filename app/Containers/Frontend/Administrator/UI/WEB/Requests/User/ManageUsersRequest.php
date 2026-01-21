<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\User;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Ship\Parents\Requests\Request as ParentRequest;

final class ManageUsersRequest extends ParentRequest
{
    protected array $decode = [];

    protected array $urlParameters = [];

    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        return $this->user()->hasRole(Role::SUPER_ADMIN);
    }
}
