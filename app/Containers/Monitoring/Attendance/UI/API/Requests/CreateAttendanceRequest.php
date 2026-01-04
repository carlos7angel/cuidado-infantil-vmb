<?php

namespace App\Containers\Monitoring\Attendance\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CreateAttendanceRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}
