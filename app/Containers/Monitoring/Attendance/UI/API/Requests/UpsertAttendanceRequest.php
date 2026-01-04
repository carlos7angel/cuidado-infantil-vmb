<?php

namespace App\Containers\Monitoring\Attendance\UI\API\Requests;

use App\Containers\Monitoring\Attendance\Enums\AttendanceStatus;
use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpsertAttendanceRequest extends ParentRequest
{
    protected array $decode = [
        'child_id',
        'childcare_center_id',
    ];

    public function rules(): array
    {
        return [
            'child_id' => 'required|exists:children,id',
            'childcare_center_id' => 'required|exists:childcare_centers,id',
            'date' => 'required|date',
            'status' => 'required|string|in:' . implode(',', array_map(fn($case) => $case->value, AttendanceStatus::cases())),
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i|after:check_in_time',
            'observations' => 'nullable|string|max:1000',
        ];
    }
}

