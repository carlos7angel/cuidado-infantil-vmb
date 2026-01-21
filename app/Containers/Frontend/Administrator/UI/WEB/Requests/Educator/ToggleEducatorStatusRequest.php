<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Educator;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\Monitoring\Educator\Models\Educator;
use App\Ship\Parents\Requests\Request as ParentRequest;

final class ToggleEducatorStatusRequest extends ParentRequest
{
    protected array $decode = [];

    protected array $urlParameters = [
        'id',
    ];

    public function rules(): array
    {
        return [
            // 'id' is in route, so we can validate it here if we want, but usually route binding handles existence or we check manually
            // 'id' => 'required|exists:educators,id', // Route parameter validation is tricky in rules() sometimes
        ];
    }

    public function authorize(): bool
    {
        if (!$this->user()->hasAnyRole([Role::SUPER_ADMIN, Role::MUNICIPAL_ADMIN, Role::CHILDCARE_ADMIN])) {
            return false;
        }

        if ($this->user()->hasRole(Role::CHILDCARE_ADMIN)) {
            $id = $this->route('id');
            if ($id) {
                $educator = Educator::with('childcareCenters')->find($id);
                if ($educator && !$educator->childcareCenters->contains('id', $this->user()->childcare_center_id)) {
                    return false;
                }
            }
        }

        return true;
    }
}
