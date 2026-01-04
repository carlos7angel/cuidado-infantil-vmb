<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Incident;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ShowIncidentRequest extends ParentRequest
{
    /**
     * @var array<string>
     */
    protected array $urlParameters = [
        'incident_id',
    ];

    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        return true;
    }
}

