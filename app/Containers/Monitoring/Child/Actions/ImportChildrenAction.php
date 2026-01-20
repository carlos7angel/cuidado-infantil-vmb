<?php

namespace App\Containers\Monitoring\Child\Actions;

use App\Containers\Frontend\Administrator\UI\WEB\Requests\Child\ImportChildrenRequest;
use App\Containers\Monitoring\Child\Tasks\CreateChildTask;
use App\Containers\Monitoring\Child\Tasks\CreateChildSocialRecordTask;
use App\Containers\Monitoring\Child\Enums\GuardianType;
use App\Containers\Monitoring\Child\Tasks\CreateChildMedicalRecordTask;
use App\Containers\Monitoring\ChildEnrollment\Tasks\CreateChildEnrollmentTask;
use App\Containers\Monitoring\ChildEnrollment\Enums\EnrollmentStatus;
use App\Containers\Monitoring\Room\Tasks\ListRoomsByChildcareCenterTask;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

class ImportChildrenAction extends ParentAction
{
    public function __construct(
        private readonly CreateChildTask $createChildTask,
        private readonly CreateChildMedicalRecordTask $createChildMedicalRecordTask,
        private readonly CreateChildSocialRecordTask $createChildSocialRecordTask,
        private readonly CreateChildEnrollmentTask $createChildEnrollmentTask,
        private readonly ListRoomsByChildcareCenterTask $listRoomsByChildcareCenterTask
    ) {
    }

    public function run(ImportChildrenRequest $request): array
    {
        // $rows = $request->validated()['data'];
        $rows = $request->all()['data'];
        $childcareCenterId = $request->input('childcare_center_id');
        $roomId = $request->input('room_id');

        // If no room selected, try to get the first one from the center
        if ($childcareCenterId && empty($roomId)) {
            $rooms = $this->listRoomsByChildcareCenterTask->run($childcareCenterId);
            if ($rooms->isNotEmpty()) {
                $roomId = $rooms->first()->id;
            }
        }

        
        
        $importedCount = 0;
        $failedCount = 0;
        $failures = [];

        foreach ($rows as $row) {
            // Skip invalid rows marked by frontend, or re-validate?
            // Trust frontend/preview data but handle DB errors.
            // But verify validity flag if passed.
            if (isset($row['is_valid']) && !$row['is_valid']) {
                $failedCount++;
                $failures[] = [
                    'row' => $row,
                    'error' => implode(', ', $row['errors'] ?? ['Datos inválidos'])
                ];
                continue;
            }

            try {
                DB::beginTransaction();

                // 1. Create Child
                $childData = [
                    'first_name' => $row['nombres'],
                    'paternal_last_name' => $row['apellido_paterno'],
                    'maternal_last_name' => $row['apellido_materno'],
                    'gender' => $row['genero'], // 'male', 'female', 'other'
                    'birth_date' => $row['fecha_nacimiento'],
                    'address' => $row['direccion'],
                    'state' => $row['departamento'],
                    'city' => $row['ciudad'],
                    'municipality' => $row['municipio'],
                    // Default values for other required fields if any?
                    // Assuming others are nullable
                ];

                $child = $this->createChildTask->run($childData);

                // 2. Create Medical Record
                $medicalData = [
                    'child_id' => $child->id,
                    'has_insurance' => $row['tiene_seguro'],
                    'insurance_details' => $row['detalle_seguro'],
                    'weight' => $row['peso'],
                    'height' => $row['talla'],
                ];
                
                // Clean nulls or empty strings for numbers
                if (empty($medicalData['weight'])) $medicalData['weight'] = null;
                if (empty($medicalData['height'])) $medicalData['height'] = null;

                $this->createChildMedicalRecordTask->run($medicalData);

                // 3. Create Social Record (Default)
                $socialData = [
                    'child_id' => $child->id,
                    'guardian_type' => GuardianType::OTHER, // Default value as requested
                ];
                $this->createChildSocialRecordTask->run($socialData);

                // 4. Create Enrollment
                if ($childcareCenterId) {
                    $enrollmentData = [
                        'child_id' => $child->id,
                        'childcare_center_id' => $childcareCenterId,
                        'room_id' => $roomId,
                        'enrollment_date' => Carbon::now(),
                        'status' => EnrollmentStatus::ACTIVE,
                    ];
                    // Filter nulls if room_id is optional
                    if (empty($enrollmentData['room_id'])) {
                        unset($enrollmentData['room_id']);
                    }
                    
                    $this->createChildEnrollmentTask->run($enrollmentData);
                }

                DB::commit();
                $importedCount++;

            } catch (Exception $e) {
                DB::rollBack();
                $failedCount++;
                $failures[] = [
                    'row' => $row,
                    'error' => $e->getMessage()
                ];
            }
        }

        return [
            'total_processed' => count($rows),
            'imported_count' => $importedCount,
            'failed_count' => $failedCount,
            'failures' => $failures
        ];
    }
}
