<?php

namespace App\Containers\Monitoring\Attendance\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListAttendancesRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}
