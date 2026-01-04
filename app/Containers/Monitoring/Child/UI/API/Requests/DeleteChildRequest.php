<?php

namespace App\Containers\Monitoring\Child\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class DeleteChildRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}
