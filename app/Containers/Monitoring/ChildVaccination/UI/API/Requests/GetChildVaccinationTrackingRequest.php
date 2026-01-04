<?php

namespace App\Containers\Monitoring\ChildVaccination\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class GetChildVaccinationTrackingRequest extends ParentRequest
{
    protected array $decode = [
        'child_id',
    ];

    public function rules(): array
    {
        return [
            'child_id' => 'exists:children,id',
        ];
    }
}

