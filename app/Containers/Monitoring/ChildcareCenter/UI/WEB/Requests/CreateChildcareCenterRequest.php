<?php

namespace App\Containers\Monitoring\ChildcareCenter\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CreateChildcareCenterRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}
