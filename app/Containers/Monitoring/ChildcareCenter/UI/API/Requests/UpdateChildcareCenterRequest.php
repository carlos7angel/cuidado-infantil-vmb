<?php

namespace App\Containers\Monitoring\ChildcareCenter\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateChildcareCenterRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}
