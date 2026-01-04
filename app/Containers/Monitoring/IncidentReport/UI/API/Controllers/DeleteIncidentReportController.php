<?php

namespace App\Containers\Monitoring\IncidentReport\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\IncidentReport\Actions\DeleteIncidentReportAction;
use App\Containers\Monitoring\IncidentReport\UI\API\Requests\DeleteIncidentReportRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class DeleteIncidentReportController extends ApiController
{
    public function __invoke(DeleteIncidentReportRequest $request, DeleteIncidentReportAction $action): JsonResponse
    {
        $action->run($request);

        return Response::noContent();
    }
}
