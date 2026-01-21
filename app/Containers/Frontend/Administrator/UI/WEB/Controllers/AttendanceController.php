<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Controllers;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Attendance\GetAttendanceReportRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Attendance\GenerateAttendanceXlsReportRequest;
use App\Containers\Monitoring\Attendance\Actions\GenerateAttendanceXlsReportAction;
use App\Containers\Monitoring\Attendance\Tasks\GetAttendanceReportTask;
use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Ship\Parents\Controllers\WebController;
use Carbon\Carbon;
use Http\Discovery\Exception\NotFoundException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Containers\AppSection\User\Models\User;
use Illuminate\Support\Facades\Auth;

final class AttendanceController extends WebController
{
    public function report(GetAttendanceReportRequest $request): View|RedirectResponse
    {
        $page_title = 'Reporte de Asistencia';
        
        /** @var User $user */
        $user = Auth::user();
        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            $childcare_centers = ChildcareCenter::where('id', $user->childcare_center_id)->get();
        } else {
            $childcare_centers = ChildcareCenter::orderBy('name')->get();
        }
        
        // Set default dates: current week (Monday to Sunday)
        $now = Carbon::now();
        $dayOfWeek = $now->dayOfWeek; // 0 = domingo, 1 = lunes, ..., 6 = sábado
        $daysToSubtract = $dayOfWeek === 0 ? 6 : $dayOfWeek - 1;
        $defaultStartDateObj = $now->copy()->subDays($daysToSubtract)->startOfDay();
        $defaultStartDate = $defaultStartDateObj->format('d/m/Y');
        $defaultEndDate = $defaultStartDateObj->copy()->addDays(6)->endOfDay()->format('d/m/Y');
        
        // Usar fechas de la request si existen, sino usar las por defecto
        $startDate = $request->input('start_date', $defaultStartDate);
        $endDate = $request->input('end_date', $defaultEndDate);
        
        // Merge defaults into request so Task can use them
        $request->merge([
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
        
        $reportData = null;
        
        // Si se presiona el botón de descarga, redirigir a la ruta de descarga
        if ($request->has('action') && $request->input('action') === 'download') {
            $queryParams = $request->only(['childcare_center_id', 'start_date', 'end_date', 'report_type']);
            // Si no hay start_date o end_date en la request, usar los defaults
            if (!isset($queryParams['start_date'])) {
                $queryParams['start_date'] = $defaultStartDate;
            }
            if (!isset($queryParams['end_date'])) {
                $queryParams['end_date'] = $defaultEndDate;
            }
            return redirect()->route('admin.attendance.report.download', $queryParams);
        }
        
        // Generar reporte automáticamente (siempre que no sea acción de descarga)
        try {
            // El request ya valida y autoriza
            // También hace merge del childcare_center_id si es childcare_admin
            
            $reportData = app(GetAttendanceReportTask::class)->run($request);
        } catch (\Exception $e) {
            // Handle other exceptions silently, no report will be shown
            $reportData = null;
        }

        return view('frontend@administrator::attendance.report', compact('page_title', 'childcare_centers', 'reportData', 'defaultStartDate', 'defaultEndDate'));
    }

    public function generateAttendanceXlsReport(GenerateAttendanceXlsReportRequest $request)
    {
        try {
            return app(GenerateAttendanceXlsReportAction::class)->run($request);
        } catch (\Exception $e) {
            throw new NotFoundException('No se pudo generar el archivo PDF');
        }
    }
}

