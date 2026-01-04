<?php

namespace App\Containers\Monitoring\NutritionalAssessment\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CreateNutritionalAssessmentRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}
