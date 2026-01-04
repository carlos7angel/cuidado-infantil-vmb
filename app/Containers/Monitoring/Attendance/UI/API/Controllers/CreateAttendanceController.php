<?php

namespace App\Containers\Monitoring\Attendance\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\Attendance\Actions\CreateAttendanceAction;
use App\Containers\Monitoring\Attendance\UI\API\Requests\CreateAttendanceRequest;
use App\Containers\Monitoring\Attendance\UI\API\Transformers\AttendanceTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class CreateAttendanceController extends ApiController
{
    public function __invoke(CreateAttendanceRequest $request, CreateAttendanceAction $action): JsonResponse
    {
        $attendance = $action->run($request);

        return Response::create($attendance, AttendanceTransformer::class)->created();
    }
}
