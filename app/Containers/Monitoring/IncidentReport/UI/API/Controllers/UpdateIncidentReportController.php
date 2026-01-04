<?php

namespace App\Containers\Monitoring\IncidentReport\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\IncidentReport\Actions\UpdateIncidentReportAction;
use App\Containers\Monitoring\IncidentReport\UI\API\Requests\UpdateIncidentReportRequest;
use App\Containers\Monitoring\IncidentReport\UI\API\Transformers\IncidentReportTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class UpdateIncidentReportController extends ApiController
{
    public function __invoke(UpdateIncidentReportRequest $request, UpdateIncidentReportAction $action): JsonResponse
    {
        $incidentReport = $action->run($request);

        return Response::create($incidentReport, IncidentReportTransformer::class)->ok();
    }
}
