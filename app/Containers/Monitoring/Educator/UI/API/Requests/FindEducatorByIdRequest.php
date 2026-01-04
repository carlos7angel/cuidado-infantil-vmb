<?php

namespace App\Containers\Monitoring\Educator\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class FindEducatorByIdRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}
