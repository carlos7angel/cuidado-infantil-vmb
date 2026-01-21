<?php

namespace App\Containers\Monitoring\Attendance\Tasks;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\Monitoring\ChildEnrollment\Data\Repositories\ChildEnrollmentRepository;
use App\Containers\Monitoring\ChildEnrollment\Enums\EnrollmentStatus;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

final class GetAttendanceReportTask extends ParentTask
{
    public function __construct(
        private readonly ChildEnrollmentRepository $enrollmentRepository,
    ) {
    }

    public function run(object $request): array
    {
        $childcareCenterId = $request->input('childcare_center_id');

        // If user is childcare_admin, force scoping to their center
        /** @var User $user */
        $user =  Auth::user();    

        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            $childcareCenterId = $user->childcare_center_id;
        }
        
        // Parse dates from d/m/Y to Y-m-d
        $startDate = Carbon::createFromFormat('d/m/Y', $request->input('start_date'));
        $endDate = Carbon::createFromFormat('d/m/Y', $request->input('end_date'));

        // Get active enrollments - filter by center if provided, otherwise get all
        $enrollmentsQuery = $this->enrollmentRepository
            ->where('status', EnrollmentStatus::ACTIVE->value)
            ->with(['child', 'room', 'childcareCenter']);
        
        if ($childcareCenterId) {
            $enrollmentsQuery->where('childcare_center_id', $childcareCenterId);
        }
        
        $enrollments = $enrollmentsQuery->get();
        
        // Sort by paternal_last_name, maternal_last_name, first_name
        $enrollments = $enrollments->sortBy(function ($enrollment) {
            $child = $enrollment->child;
            return [
                $child->paternal_last_name ?? '',
                $child->maternal_last_name ?? '',
                $child->first_name ?? '',
            ];
        })->values();

        // Generate array of dates in range
        $dates = [];
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        // Get all attendance records for the date range and children
        $childIds = $enrollments->pluck('child_id')->toArray();
        $attendanceQuery = \App\Containers\Monitoring\Attendance\Models\Attendance::whereIn('child_id', $childIds)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
        
        if ($childcareCenterId) {
            $attendanceQuery->where('childcare_center_id', $childcareCenterId);
        }
        
        $attendanceRecords = $attendanceQuery->get();

        // Group by child_id, childcare_center_id and date
        $attendances = [];
        foreach ($attendanceRecords as $attendance) {
            $dateKey = $attendance->date->format('Y-m-d');
            $attendances[$attendance->child_id][$attendance->childcare_center_id][$dateKey] = $attendance;
        }

        // Build report data
        $reportData = [];
        foreach ($enrollments as $enrollment) {
            $child = $enrollment->child;
            $row = [
                'child_id' => $child->id,
                'child_name' => trim($child->first_name . ' ' . $child->paternal_last_name . ' ' . $child->maternal_last_name),
                'childcare_center_name' => $enrollment->childcareCenter ? $enrollment->childcareCenter->name : '-',
                'room_name' => $enrollment->room ? $enrollment->room->name : '-',
                'attendance' => [],
            ];

            // Fill attendance for each date
            // When showing all centers, we need to match by both child_id and childcare_center_id
            foreach ($dates as $date) {
                $dateKey = $date;
                $attendanceRecord = null;
                
                if (isset($attendances[$child->id][$enrollment->childcare_center_id][$dateKey])) {
                    $attendanceRecord = $attendances[$child->id][$enrollment->childcare_center_id][$dateKey];
                }
                
                if ($attendanceRecord) {
                    $row['attendance'][$dateKey] = [
                        'status' => $attendanceRecord->status ? $attendanceRecord->status->value : null,
                        'check_in_time' => $attendanceRecord->check_in_time ? $attendanceRecord->check_in_time->format('H:i') : null,
                        'check_out_time' => $attendanceRecord->check_out_time ? $attendanceRecord->check_out_time->format('H:i') : null,
                    ];
                } else {
                    $row['attendance'][$dateKey] = [
                        'status' => null,
                        'check_in_time' => null,
                        'check_out_time' => null,
                    ];
                }
            }

            $reportData[] = $row;
        }

        return [
            'dates' => $dates,
            'children' => $reportData,
            'childcare_center' => $childcareCenterId ? \App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter::find($childcareCenterId) : null,
            'show_all_centers' => !$childcareCenterId,
        ];
    }
}

