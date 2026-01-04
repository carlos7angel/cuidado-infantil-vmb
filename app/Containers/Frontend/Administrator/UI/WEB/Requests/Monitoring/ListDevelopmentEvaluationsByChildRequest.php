<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Monitoring;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListDevelopmentEvaluationsByChildRequest extends ParentRequest
{
    protected array $decode = [];

    protected array $urlParameters = [
        'id',
    ];

    public function rules(): array
    {
        return [
            // 'id' => 'required|exists:children,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

