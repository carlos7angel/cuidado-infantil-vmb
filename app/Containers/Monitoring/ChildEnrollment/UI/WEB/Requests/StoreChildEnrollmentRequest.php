<?php

namespace App\Containers\Monitoring\ChildEnrollment\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class StoreChildEnrollmentRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}
