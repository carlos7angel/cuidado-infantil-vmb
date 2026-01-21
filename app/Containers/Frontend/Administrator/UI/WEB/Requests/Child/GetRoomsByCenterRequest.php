<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Child;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Ship\Parents\Requests\Request as ParentRequest;

final class GetRoomsByCenterRequest extends ParentRequest
{
    protected array $urlParameters = [
        'childcare_center_id',
    ];

    public function rules(): array
    {
        return [
            // 'childcare_center_id' => 'required|exists:childcare_centers,id',
        ];
    }

    public function authorize(): bool
    {
        $centerId = $this->route('childcare_center_id');
        $user = $this->user();

        if (!$user->hasAnyRole([Role::SUPER_ADMIN, Role::MUNICIPAL_ADMIN, Role::CHILDCARE_ADMIN])) {
            return false;
        }

        if ($user->hasRole(Role::CHILDCARE_ADMIN) && $user->childcare_center_id != $centerId) {
            return false;
        }

        return true;
    }
}
