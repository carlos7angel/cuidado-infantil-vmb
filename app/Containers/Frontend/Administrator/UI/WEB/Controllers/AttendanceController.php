<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Controllers;

use App\Containers\Frontend\Administrator\UI\WEB\Requests\Attendance\GenerateAttendanceXlsReportRequest;
use App\Containers\Monitoring\Attendance\Actions\GenerateAttendanceXlsReportAction;
use App\Containers\Monitoring\Attendance\Tasks\GetAttendanceReportTask;
use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Ship\Parents\Controllers\WebController;
use Carbon\Carbon;
use Http\Discovery\Exception\NotFoundException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class AttendanceController extends WebController
{
    public function report(Request $request): View|RedirectResponse
    {
        $page_title = 'Reporte de Asistencia';
        $childcare_centers = ChildcareCenter::orderBy('name')->get();
        
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
            // Crear datos de validación usando fechas de request o defaults
            $requestData = [
                'childcare_center_id' => $request->input('childcare_center_id'),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'report_type' => $request->input('report_type', 'complete'),
            ];
            
            // Validate the request
            $validatedData = validator($requestData, [
                'childcare_center_id' => 'nullable|exists:childcare_centers,id',
                'start_date' => 'required|date_format:d/m/Y',
                'end_date' => 'required|date_format:d/m/Y|after_or_equal:start_date',
                'report_type' => 'nullable|in:complete,simplified',
            ])->validate();
            
            // Create a request-like object for the task
            $reportRequest = new class($validatedData) {
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
            
            $reportData = app(GetAttendanceReportTask::class)->run($reportRequest);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return back with validation errors
            return view('frontend@administrator::attendance.report', compact('page_title', 'childcare_centers', 'reportData', 'defaultStartDate', 'defaultEndDate'))
                ->with('errors', $e->errors());
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

