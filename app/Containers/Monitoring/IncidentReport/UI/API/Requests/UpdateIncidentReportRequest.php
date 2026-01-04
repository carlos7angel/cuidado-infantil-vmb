<?php

namespace App\Containers\Monitoring\IncidentReport\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateIncidentReportRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}
