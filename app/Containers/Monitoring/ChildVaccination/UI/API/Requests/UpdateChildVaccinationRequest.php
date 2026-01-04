<?php

namespace App\Containers\Monitoring\ChildVaccination\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateChildVaccinationRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}
