<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\User;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateUserInfoRequest extends ParentRequest
{
    protected array $decode = [
        // 'user_id',
    ];

    public function rules(): array
    {
        return [
            'user_id' => 'exists:users,id',
            'name' => 'required|string|max:255',
            'active' => 'required|boolean',
            'childcare_center_id' => 'nullable|exists:childcare_centers,id',
        ];
    }

    public function authorize(): bool
    {
        return $this->user()->hasRole(Role::SUPER_ADMIN);
    }
}
