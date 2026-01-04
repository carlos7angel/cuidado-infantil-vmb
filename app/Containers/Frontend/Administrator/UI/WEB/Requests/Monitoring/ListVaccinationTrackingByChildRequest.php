<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Monitoring;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListVaccinationTrackingByChildRequest extends ParentRequest
{
    protected array $decode = [];

    protected array $urlParameters = [
        'child_id',
    ];

    public function rules(): array
    {
        return [
            // 'child_id' => 'required|exists:children,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

