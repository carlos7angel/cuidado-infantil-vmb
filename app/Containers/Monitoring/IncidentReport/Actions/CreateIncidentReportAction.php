<?php

namespace App\Containers\Monitoring\IncidentReport\Actions;

use App\Containers\AppSection\File\Tasks\CreateFileTask;
use App\Containers\Monitoring\Child\Data\Repositories\ChildRepository;
use App\Containers\Monitoring\IncidentReport\Models\IncidentReport;
use App\Containers\Monitoring\IncidentReport\Tasks\CreateIncidentReportTask;
use App\Containers\Monitoring\IncidentReport\UI\API\Requests\CreateIncidentReportRequest;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\DB;

final class CreateIncidentReportAction extends ParentAction
{
    public function __construct(
        private readonly CreateIncidentReportTask $createIncidentReportTask,
        private readonly ChildRepository $childRepository,
        private readonly CreateFileTask $createFileTask,
    ) {
    }

    public function run(CreateIncidentReportRequest $request): IncidentReport
    {
        // Obtener el usuario autenticado
        $user = $request->user();

        // Obtener el niño y su enrollment activo
        $childId = $request->input('child_id');
        $child = $this->childRepository->findOrFail($childId);
        
        // Obtener el enrollment activo del niño
        $activeEnrollment = $child->activeEnrollment;

        // Obtener childcare_center_id y room_id del enrollment activo si no se proporcionaron
        $childcareCenterId = $request->input('childcare_center_id');
        $roomId = $request->input('room_id');

        if (!$childcareCenterId && $activeEnrollment) {
            $childcareCenterId = $activeEnrollment->childcare_center_id;
        }

        if (!$roomId && $activeEnrollment) {
            $roomId = $activeEnrollment->room_id;
        }

        return DB::transaction(function () use ($request, $childId, $user, $childcareCenterId, $roomId) {
            // Preparar los datos para crear el reporte
            $data = [
                'child_id' => $childId,
                'type' => $request->input('type'),
                'severity_level' => $request->input('severity_level'),
                'description' => $request->input('description'),
                'incident_date' => $request->input('incident_date'),
                'incident_time' => $request->input('incident_time'),
                'incident_location' => $request->input('incident_location'),
                'people_involved' => $request->input('people_involved'),
                'reported_by' => $user->id,
                'status' => 'reportado', // Estado por defecto
                
                // Campos opcionales
                'witnesses' => $request->input('witnesses'),
                'has_evidence' => $request->input('has_evidence', false),
                'evidence_description' => $request->input('evidence_description'),
                'evidence_file_ids' => null, // Se actualizará después si hay archivos
                'actions_taken' => $request->input('actions_taken'),
                'additional_comments' => $request->input('additional_comments'),
                'escalated_to' => $request->input('escalated_to'),
                'childcare_center_id' => $childcareCenterId,
                'room_id' => $roomId,
            ];

            // Crear el reporte primero (necesario para tener el ID y asociar archivos)
            // Nota: reported_at se establece automáticamente en el modelo mediante el evento 'creating'
            $incidentReport = $this->createIncidentReportTask->run($data);

            // Procesar archivos de evidencia si se proporcionaron
            $evidenceFileIds = [];
            if ($request->hasFile('evidence_files')) {
                $files = is_array($request->file('evidence_files'))
                    ? $request->file('evidence_files')
                    : [$request->file('evidence_files')];

                foreach ($files as $file) {
                    if ($file && $file->isValid()) {
                        // Asociar archivos al incident report (no al child)
                        $uploadedFile = $this->createFileTask->run(
                            $file,
                            $incidentReport, // Pasar el modelo IncidentReport
                            $user->id
                        );
                        $evidenceFileIds[] = (string) $uploadedFile->id;
                    }
                }
                
                // Actualizar el reporte con los IDs de archivos y has_evidence
                if (!empty($evidenceFileIds)) {
                    $incidentReport->evidence_file_ids = $evidenceFileIds;
                    $incidentReport->has_evidence = true;
                    $incidentReport->save();
                }
            }

            // Cargar relaciones necesarias para el transformer
            $incidentReport->load(['child', 'reportedBy', 'childcareCenter', 'room']);

            return $incidentReport;
        });
    }
}
