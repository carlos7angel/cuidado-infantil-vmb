<?php

namespace App\Containers\AppSection\ActivityLog\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListActivityLogsRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}
