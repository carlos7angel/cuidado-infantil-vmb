<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\ChildcareCenter;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class GetChildcareCentersJsonDataTableRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}
