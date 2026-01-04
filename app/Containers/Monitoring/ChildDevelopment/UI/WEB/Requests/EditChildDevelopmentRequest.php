<?php

namespace App\Containers\Monitoring\ChildDevelopment\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class EditChildDevelopmentRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}
