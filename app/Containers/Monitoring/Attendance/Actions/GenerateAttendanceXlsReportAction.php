<?php

namespace App\Containers\Monitoring\Attendance\Actions;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Attendance\GenerateAttendanceXlsReportRequest;
use App\Containers\Monitoring\Attendance\Reports\Exports\AttendanceXlsReportExport;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

final class GenerateAttendanceXlsReportAction extends ParentAction
{
    public function __construct(
        
    ) {
    }

    public function run(GenerateAttendanceXlsReportRequest $request): mixed
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $childcareCenterId = $request->input('childcare_center_id');
        $reportType = $request->input('report_type', 'complete');
        
        // If user is childcare_admin, force scoping to their center
        /** @var User $user */
        $user = Auth::user();

        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            $childcareCenterId = $user->childcare_center_id;
        }

        $filename = 'Asistencia_' . Str::slug($startDate) . '_' . Str::slug($endDate) . '.xlsx';
        
        // Create a request-like object for the export
        $reportRequest = new class([
            'childcare_center_id' => $childcareCenterId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'report_type' => $reportType,
        ]) {
            private $data;
            
            public function __construct($data) {
                $this->data = $data;
            }
            
            public function input($key = null, $default = null) {
                if ($key === null) {
                    return $this->data;
                }
                return $this->data[$key] ?? $default;
            }
        };
        
        return Excel::download(new AttendanceXlsReportExport($reportRequest), $filename);
    }
}
