<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Attendance;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class GenerateAttendanceXlsReportRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'childcare_center_id' => 'nullable|exists:childcare_centers,id',
            'start_date' => 'required|date_format:d/m/Y',
            'end_date' => 'required|date_format:d/m/Y|after_or_equal:start_date',
            'report_type' => 'nullable|in:complete,simplified',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

