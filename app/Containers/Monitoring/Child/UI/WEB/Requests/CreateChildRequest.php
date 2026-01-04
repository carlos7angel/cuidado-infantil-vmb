<?php

namespace App\Containers\Monitoring\Child\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CreateChildRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}
