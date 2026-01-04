<?php

namespace App\Containers\AppSection\ActivityLog\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateActivityLogRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}
