<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Incident;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class GetIncidentsJsonDataTableRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}

