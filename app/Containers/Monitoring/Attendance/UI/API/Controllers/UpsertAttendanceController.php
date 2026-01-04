<?php

namespace App\Containers\Monitoring\Attendance\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\Attendance\Actions\UpsertAttendanceAction;
use App\Containers\Monitoring\Attendance\UI\API\Requests\UpsertAttendanceRequest;
use App\Containers\Monitoring\Attendance\UI\API\Transformers\AttendanceTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class UpsertAttendanceController extends ApiController
{
    public function __invoke(
        UpsertAttendanceRequest $request,
        UpsertAttendanceAction $action
    ): JsonResponse {
        $attendance = $action->run($request);

        return Response::create($attendance, AttendanceTransformer::class)->ok();
    }
}

