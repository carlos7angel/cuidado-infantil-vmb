<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Incident;

use App\Containers\Monitoring\IncidentReport\Enums\IncidentStatus;
use App\Ship\Parents\Requests\Request as ParentRequest;
use Illuminate\Validation\Rule;

final class UpdateIncidentStatusRequest extends ParentRequest
{
    protected array $urlParameters = [
        'incident_id',
    ];

    protected array $decode = [
        // 'incident_id',
    ];

    public function rules(): array
    {
        return [
            'incident_id' => ['string', 'exists:incident_reports,id'],
            'status' => ['required', 'string', Rule::in(array_column(IncidentStatus::cases(), 'value'))],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

