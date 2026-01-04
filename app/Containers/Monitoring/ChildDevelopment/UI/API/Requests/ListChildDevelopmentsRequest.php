<?php

namespace App\Containers\Monitoring\ChildDevelopment\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListChildDevelopmentsRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}
