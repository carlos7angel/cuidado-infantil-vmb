<?php

namespace App\Containers\Monitoring\Attendance\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\Attendance\Actions\ListAttendancesAction;
use App\Containers\Monitoring\Attendance\UI\API\Requests\ListAttendancesRequest;
use App\Containers\Monitoring\Attendance\UI\API\Transformers\AttendanceTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class ListAttendancesController extends ApiController
{
    public function __invoke(ListAttendancesRequest $request, ListAttendancesAction $action): JsonResponse
    {
        $attendances = $action->run($request);

        return Response::create($attendances, AttendanceTransformer::class)->ok();
    }
}
