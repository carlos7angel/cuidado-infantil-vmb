<?php

namespace App\Containers\Monitoring\Attendance\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\Attendance\Actions\ListChildrenAttendanceByDateRangeAction;
use App\Containers\Monitoring\Attendance\UI\API\Requests\ListChildrenAttendanceByDateRangeRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class ListChildrenAttendanceByDateRangeController extends ApiController
{
    public function __invoke(
        ListChildrenAttendanceByDateRangeRequest $request,
        ListChildrenAttendanceByDateRangeAction $action
    ): JsonResponse {
        $data = $action->run($request);

        return Response::json(['data' => $data]);
    }
}

