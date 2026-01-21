<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\User;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateUserRequest extends ParentRequest
{
    protected array $decode = [
        // 'user_id',
    ];

    public function rules(): array
    {
        return [
            'user_id' => 'exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->get('user_id'),
            'user_role' => 'required|in:municipal_admin,childcare_admin',
            'childcare_center_id' => 'nullable|exists:childcare_centers,id',
            'active' => 'required|boolean',
            'current_password' => 'nullable|string|min:1',
            'new_password' => 'nullable|string|min:8',
            'confirm_password' => 'nullable|string|same:new_password',
        ];
    }

    public function authorize(): bool
    {
        return $this->user()->hasRole(Role::SUPER_ADMIN);
    }
}
