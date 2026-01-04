<?php

namespace App\Containers\Monitoring\Educator\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\Educator\Actions\FindEducatorByIdAction;
use App\Containers\Monitoring\Educator\UI\API\Requests\FindEducatorByIdRequest;
use App\Containers\Monitoring\Educator\UI\API\Transformers\EducatorTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class FindEducatorByIdController extends ApiController
{
    public function __invoke(FindEducatorByIdRequest $request, FindEducatorByIdAction $action): JsonResponse
    {
        $educator = $action->run($request);

        return Response::create($educator, EducatorTransformer::class)->ok();
    }
}
