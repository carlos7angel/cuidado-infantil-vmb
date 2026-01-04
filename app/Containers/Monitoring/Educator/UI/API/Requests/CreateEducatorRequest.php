<?php

namespace App\Containers\Monitoring\Educator\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CreateEducatorRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}
