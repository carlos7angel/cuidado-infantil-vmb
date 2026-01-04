<?php

namespace App\Containers\Monitoring\Attendance\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\Attendance\Actions\DeleteAttendanceAction;
use App\Containers\Monitoring\Attendance\UI\API\Requests\DeleteAttendanceRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class DeleteAttendanceController extends ApiController
{
    public function __invoke(DeleteAttendanceRequest $request, DeleteAttendanceAction $action): JsonResponse
    {
        $action->run($request);

        return Response::noContent();
    }
}
