<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Educator;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\Monitoring\Educator\Models\Educator;
use App\Ship\Parents\Requests\Request as ParentRequest;

final class FormEducatorRequest extends ParentRequest
{
    protected array $decode = [];

    protected array $urlParameters = [
        'id',
    ];

    public function rules(): array
    {
        return [
            'id' => 'sometimes|exists:educators,id',
        ];
    }

    public function authorize(): bool
    {
        if (!$this->user()->hasAnyRole([Role::SUPER_ADMIN, Role::MUNICIPAL_ADMIN, Role::CHILDCARE_ADMIN])) {
            return false;
        }

        if ($this->user()->hasRole(Role::CHILDCARE_ADMIN) && $this->id) {
            $educator = Educator::with('childcareCenters')->find($this->id);
            if ($educator && !$educator->childcareCenters->contains('id', $this->user()->childcare_center_id)) {
                return false;
            }
        }

        return true;
    }
}
