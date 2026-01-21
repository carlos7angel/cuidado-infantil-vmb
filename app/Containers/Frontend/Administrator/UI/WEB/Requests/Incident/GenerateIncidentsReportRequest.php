<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Incident;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Ship\Parents\Requests\Request as ParentRequest;

final class GenerateIncidentsReportRequest extends ParentRequest
{
    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        return $this->user()->hasAnyRole([
            Role::SUPER_ADMIN,
            Role::MUNICIPAL_ADMIN,
            Role::CHILDCARE_ADMIN,
        ]);
    }
}
