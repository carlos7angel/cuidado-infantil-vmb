<?php

namespace App\Containers\Monitoring\ChildDevelopment\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CreateChildDevelopmentRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}
