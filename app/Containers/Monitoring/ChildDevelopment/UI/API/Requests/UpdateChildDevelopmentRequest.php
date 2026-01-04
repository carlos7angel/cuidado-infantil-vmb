<?php

namespace App\Containers\Monitoring\ChildDevelopment\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateChildDevelopmentRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}
