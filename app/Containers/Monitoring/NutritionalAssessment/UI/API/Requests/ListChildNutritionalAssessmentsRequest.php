<?php

namespace App\Containers\Monitoring\NutritionalAssessment\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListChildNutritionalAssessmentsRequest extends ParentRequest
{
    protected array $decode = [
        'child_id', // Decodificar el ID hasheado del niño desde la URL
    ];

    public function rules(): array
    {
        return [];
    }
}

