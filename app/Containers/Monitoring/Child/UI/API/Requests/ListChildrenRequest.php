<?php

namespace App\Containers\Monitoring\Child\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListChildrenRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}
