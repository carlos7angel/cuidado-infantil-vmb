<?php

namespace App\Containers\Monitoring\IncidentReport\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\IncidentReport\Actions\CreateIncidentReportAction;
use App\Containers\Monitoring\IncidentReport\UI\API\Requests\CreateIncidentReportRequest;
use App\Containers\Monitoring\IncidentReport\UI\API\Transformers\IncidentReportTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class CreateIncidentReportController extends ApiController
{
    public function __invoke(CreateIncidentReportRequest $request, CreateIncidentReportAction $action): JsonResponse
    {
        $incidentReport = $action->run($request);

        return Response::create($incidentReport, IncidentReportTransformer::class)->created();
    }
}
