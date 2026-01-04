<?php

namespace App\Containers\Monitoring\IncidentReport\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListIncidentReportsRequest extends ParentRequest
{
    protected array $decode = [
        'childcare_center_id',
    ];

    protected array $urlParameters = [
        'childcare_center_id',
    ];

    public function rules(): array
    {
        return [
            'childcare_center_id' => 'exists:childcare_centers,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
