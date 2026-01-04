<?php

namespace App\Containers\Monitoring\Attendance\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateAttendanceRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}
