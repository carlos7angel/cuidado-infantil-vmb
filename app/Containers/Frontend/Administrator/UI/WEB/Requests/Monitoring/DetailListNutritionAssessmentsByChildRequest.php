<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Monitoring;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class DetailListNutritionAssessmentsByChildRequest extends ParentRequest
{
    protected array $decode = [
        'nutritional_assessment_id',
    ];

    protected array $urlParameters = [
        'nutritional_assessment_id',
    ];

    public function rules(): array
    {
        return [
            // 'nutritional_assessment_id' => 'required|exists:nutritional_assessments,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

