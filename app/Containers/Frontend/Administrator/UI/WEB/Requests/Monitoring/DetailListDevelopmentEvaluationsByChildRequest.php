<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Monitoring;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class DetailListDevelopmentEvaluationsByChildRequest extends ParentRequest
{
    protected array $decode = [
        'development_evaluation_id',
    ];

    protected array $urlParameters = [
        'development_evaluation_id',
    ];

    public function rules(): array
    {
        return [
            // 'development_evaluation_id' => 'required|exists:child_development_evaluations,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

