<?php

namespace App\Containers\Monitoring\NutritionalAssessment\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class DeleteNutritionalAssessmentRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}
