<?php

namespace App\Containers\Monitoring\Child\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class EditChildRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}
