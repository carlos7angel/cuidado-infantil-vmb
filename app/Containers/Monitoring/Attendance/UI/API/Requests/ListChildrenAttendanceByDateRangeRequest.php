<?php

namespace App\Containers\Monitoring\Attendance\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;
use Carbon\Carbon;

final class ListChildrenAttendanceByDateRangeRequest extends ParentRequest
{
    
    protected array $decode = [
        'childcare_center_id',
    ];

    public function rules(): array
    {
        return [
            // 'childcare_center_id' => 'required|exists:childcare_centers,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }

    /**
     * Get start date from request or use default (6 days ago).
     * Default range: 6 days ago + today + tomorrow = 8 days total.
     */
    public function getStartDate(): string
    {
        return $this->input('start_date', Carbon::now()->subDays(6)->format('Y-m-d'));
    }

    /**
     * Get end date from request or use default (tomorrow).
     * Default range: 6 days ago + today + tomorrow = 8 days total.
     */
    public function getEndDate(): string
    {
        return $this->input('end_date', Carbon::now()->addDay()->format('Y-m-d'));
    }
}

