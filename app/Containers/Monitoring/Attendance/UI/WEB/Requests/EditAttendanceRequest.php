<?php

namespace App\Containers\Monitoring\Attendance\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class EditAttendanceRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}
