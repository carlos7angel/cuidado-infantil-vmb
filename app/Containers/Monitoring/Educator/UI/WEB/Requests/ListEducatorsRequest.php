<?php

namespace App\Containers\Monitoring\Educator\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListEducatorsRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}
