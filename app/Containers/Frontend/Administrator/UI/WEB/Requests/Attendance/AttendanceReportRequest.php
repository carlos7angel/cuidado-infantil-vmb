<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Attendance;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class AttendanceReportRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'childcare_center_id' => 'sometimes|required|exists:childcare_centers,id',
            'start_date' => 'sometimes|required|date_format:d/m/Y',
            'end_date' => 'sometimes|required|date_format:d/m/Y|after_or_equal:start_date',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

