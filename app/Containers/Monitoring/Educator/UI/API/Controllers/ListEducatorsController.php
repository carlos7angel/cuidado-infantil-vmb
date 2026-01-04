<?php

namespace App\Containers\Monitoring\Educator\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\Educator\Actions\ListEducatorsAction;
use App\Containers\Monitoring\Educator\UI\API\Requests\ListEducatorsRequest;
use App\Containers\Monitoring\Educator\UI\API\Transformers\EducatorTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class ListEducatorsController extends ApiController
{
    public function __invoke(ListEducatorsRequest $request, ListEducatorsAction $action): JsonResponse
    {
        $educators = $action->run($request);

        return Response::create($educators, EducatorTransformer::class)->ok();
    }
}
