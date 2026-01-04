<?php

namespace App\Containers\Monitoring\ChildEnrollment\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CreateChildEnrollmentRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}
