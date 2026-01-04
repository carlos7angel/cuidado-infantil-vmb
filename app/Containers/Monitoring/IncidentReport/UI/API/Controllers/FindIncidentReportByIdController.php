<?php

namespace App\Containers\Monitoring\IncidentReport\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\IncidentReport\Actions\FindIncidentReportByIdAction;
use App\Containers\Monitoring\IncidentReport\UI\API\Requests\FindIncidentReportByIdRequest;
use App\Containers\Monitoring\IncidentReport\UI\API\Transformers\IncidentReportTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class FindIncidentReportByIdController extends ApiController
{
    public function __invoke(FindIncidentReportByIdRequest $request, FindIncidentReportByIdAction $action): JsonResponse
    {
        $incidentReport = $action->run($request);

        return Response::create($incidentReport, IncidentReportTransformer::class)->ok();
    }
}
