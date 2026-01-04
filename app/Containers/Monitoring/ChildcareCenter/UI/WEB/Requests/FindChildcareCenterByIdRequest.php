<?php

namespace App\Containers\Monitoring\ChildcareCenter\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class FindChildcareCenterByIdRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}
