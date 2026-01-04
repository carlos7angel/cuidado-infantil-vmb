<?php

namespace App\Containers\Monitoring\NutritionalAssessment\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListNutritionalAssessmentsRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}
