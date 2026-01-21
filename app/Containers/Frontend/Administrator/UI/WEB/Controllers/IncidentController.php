<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Controllers;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Incident\GetIncidentsJsonDataTableRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Incident\GenerateIncidentsReportRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Incident\ShowIncidentRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Incident\UpdateIncidentStatusRequest;
use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Containers\Monitoring\IncidentReport\Actions\GenerateIncidentsReportAction;
use App\Containers\Monitoring\IncidentReport\Actions\GetIncidentsJsonDataTableAction;
use App\Containers\Monitoring\IncidentReport\Actions\UpdateIncidentReportStatusAction;
use App\Containers\Monitoring\IncidentReport\Enums\IncidentStatus;
use App\Containers\Monitoring\IncidentReport\Enums\IncidentType;
use App\Containers\Monitoring\IncidentReport\Tasks\FindIncidentReportByIdTask;
use App\Containers\Monitoring\Room\Models\Room;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class IncidentController extends WebController
{
    public function manage(): View
    {
        $page_title = 'Reportes de Incidentes';

        /** @var User $user */
        $user = Auth::user();

        // Get data for filters
        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            $childcareCenters = ChildcareCenter::where('id', $user->childcare_center_id)->get();
            $rooms = Room::with('childcareCenter')->where('childcare_center_id', $user->childcare_center_id)->orderBy('name')->get();
        } else {
            $childcareCenters = ChildcareCenter::orderBy('name')->get();
            $rooms = Room::with('childcareCenter')->orderBy('name')->get();
        }

        $incidentTypes = IncidentType::cases();
        $incidentStatuses = IncidentStatus::cases();

        return view('frontend@administrator::incident.manage', compact(
            'page_title',
            'childcareCenters',
            'rooms',
            'incidentTypes',
            'incidentStatuses'
        ));
    }

    public function listJsonDataTable(GetIncidentsJsonDataTableRequest $request): JsonResponse
    {
        try {
            $data = app(GetIncidentsJsonDataTableAction::class)->run($request);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show(ShowIncidentRequest $request): View
    {
        $page_title = 'Detalle del Reporte de Incidente';
        $incident = app(FindIncidentReportByIdTask::class)->run($request->incident_id);
        
        // Load relationships
        $incident->load([
            'child',
            'childcareCenter',
            'room',
            'reportedBy',
            'evaluatedBy',
            'files',
        ]);

        $incidentStatuses = IncidentStatus::options();

        return view('frontend@administrator::incident.details', compact('page_title', 'incident', 'incidentStatuses'));
    }

    public function updateStatus(UpdateIncidentStatusRequest $request): JsonResponse
    {
        try {
            $incident = app(UpdateIncidentReportStatusAction::class)->run($request);
            
            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado correctamente',
                'data' => [
                    'status' => $incident->status->value,
                    'status_label' => $incident->status->label(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateIncidentsReport(GenerateIncidentsReportRequest $request)
    {
        try {
            return app(GenerateIncidentsReportAction::class)->run();
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar el reporte: ' . $e->getMessage());
        }
    }
}

