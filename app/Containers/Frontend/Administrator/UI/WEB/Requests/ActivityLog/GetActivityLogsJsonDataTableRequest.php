<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\ActivityLog;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class GetActivityLogsJsonDataTableRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}

