<?php

namespace App\Containers\Monitoring\Attendance\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\Attendance\Actions\UpdateAttendanceAction;
use App\Containers\Monitoring\Attendance\UI\API\Requests\UpdateAttendanceRequest;
use App\Containers\Monitoring\Attendance\UI\API\Transformers\AttendanceTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class UpdateAttendanceController extends ApiController
{
    public function __invoke(UpdateAttendanceRequest $request, UpdateAttendanceAction $action): JsonResponse
    {
        $attendance = $action->run($request);

        return Response::create($attendance, AttendanceTransformer::class)->ok();
    }
}
