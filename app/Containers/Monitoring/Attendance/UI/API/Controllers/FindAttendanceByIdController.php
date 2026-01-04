<?php

namespace App\Containers\Monitoring\Attendance\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\Attendance\Actions\FindAttendanceByIdAction;
use App\Containers\Monitoring\Attendance\UI\API\Requests\FindAttendanceByIdRequest;
use App\Containers\Monitoring\Attendance\UI\API\Transformers\AttendanceTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class FindAttendanceByIdController extends ApiController
{
    public function __invoke(FindAttendanceByIdRequest $request, FindAttendanceByIdAction $action): JsonResponse
    {
        $attendance = $action->run($request);

        return Response::create($attendance, AttendanceTransformer::class)->ok();
    }
}
