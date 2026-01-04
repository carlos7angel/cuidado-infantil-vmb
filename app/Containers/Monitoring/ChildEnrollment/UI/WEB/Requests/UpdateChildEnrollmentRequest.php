<?php

namespace App\Containers\Monitoring\ChildEnrollment\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateChildEnrollmentRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}
