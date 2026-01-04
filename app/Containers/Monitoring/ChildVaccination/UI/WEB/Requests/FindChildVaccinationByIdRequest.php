<?php

namespace App\Containers\Monitoring\ChildVaccination\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class FindChildVaccinationByIdRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}
