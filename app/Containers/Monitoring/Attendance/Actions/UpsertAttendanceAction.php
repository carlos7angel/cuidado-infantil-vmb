<?php

namespace App\Containers\Monitoring\Attendance\Actions;

use App\Containers\Monitoring\Attendance\Models\Attendance;
use App\Containers\Monitoring\Attendance\Tasks\UpsertAttendanceTask;
use App\Containers\Monitoring\Attendance\UI\API\Requests\UpsertAttendanceRequest;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\Auth;

final class UpsertAttendanceAction extends ParentAction
{
    public function __construct(
        private readonly UpsertAttendanceTask $upsertAttendanceTask,
    ) {
    }

    public function run(UpsertAttendanceRequest $request): Attendance
    {
        $data = $request->sanitize([
            'child_id',
            'childcare_center_id',
            'date',
            'status',
            'check_in_time',
            'check_out_time',
            'observations',
        ]);

        // Agregar el usuario autenticado como quien registra
        $data['registered_by'] = Auth::id();

        return $this->upsertAttendanceTask->run($data);
    }
}

