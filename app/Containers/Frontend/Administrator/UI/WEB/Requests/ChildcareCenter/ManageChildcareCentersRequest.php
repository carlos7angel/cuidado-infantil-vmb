<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\ChildcareCenter;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Ship\Parents\Requests\Request as ParentRequest;

final class ManageChildcareCentersRequest extends ParentRequest
{
    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        return $this->user()->hasAnyRole([Role::SUPER_ADMIN, Role::MUNICIPAL_ADMIN]);
    }
}
