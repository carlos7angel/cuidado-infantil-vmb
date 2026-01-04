<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Educator;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class GetEducatorsJsonDataTableRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}

