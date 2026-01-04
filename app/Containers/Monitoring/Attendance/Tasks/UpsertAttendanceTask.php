<?php

namespace App\Containers\Monitoring\Attendance\Tasks;

use App\Containers\Monitoring\Attendance\Data\Repositories\AttendanceRepository;
use App\Containers\Monitoring\Attendance\Models\Attendance;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class UpsertAttendanceTask extends ParentTask
{
    public function __construct(
        private readonly AttendanceRepository $repository,
    ) {
    }

    /**
     * Create or update attendance record.
     * If a record exists with same child_id, childcare_center_id and date, update it.
     * Otherwise, create a new record.
     */
    public function run(array $data): Attendance
    {
        // Preparar datos para updateOrCreate
        $uniqueKeys = [
            'child_id' => $data['child_id'],
            'childcare_center_id' => $data['childcare_center_id'],
            'date' => $data['date'],
        ];

        // Datos a actualizar/crear
        $updateData = [
            'status' => $data['status'],
            'registered_by' => $data['registered_by'] ?? null,
        ];

        // Campos opcionales
        if (isset($data['check_in_time'])) {
            $updateData['check_in_time'] = $data['check_in_time'];
        }
        if (isset($data['check_out_time'])) {
            $updateData['check_out_time'] = $data['check_out_time'];
        }
        if (isset($data['observations'])) {
            $updateData['observations'] = $data['observations'];
        }

        // updateOrCreate: busca por las claves únicas, si existe actualiza, si no crea
        return $this->repository->updateOrCreate($uniqueKeys, $updateData);
    }
}

