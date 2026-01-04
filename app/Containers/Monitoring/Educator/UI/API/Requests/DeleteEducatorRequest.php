<?php

namespace App\Containers\Monitoring\Educator\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class DeleteEducatorRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}
