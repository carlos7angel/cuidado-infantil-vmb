<?php

namespace App\Containers\Monitoring\IncidentReport\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\IncidentReport\Actions\ListIncidentReportsAction;
use App\Containers\Monitoring\IncidentReport\UI\API\Requests\ListIncidentReportsRequest;
use App\Containers\Monitoring\IncidentReport\UI\API\Transformers\IncidentReportListTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class ListIncidentReportsController extends ApiController
{
    public function __invoke(ListIncidentReportsRequest $request, ListIncidentReportsAction $action): JsonResponse
    {
        $incidentReports = $action->run($request);

        return Response::create($incidentReports, IncidentReportListTransformer::class)->ok();
    }
}
