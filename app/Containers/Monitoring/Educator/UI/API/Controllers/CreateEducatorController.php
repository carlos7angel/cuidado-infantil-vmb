<?php

namespace App\Containers\Monitoring\Educator\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\Educator\Actions\CreateEducatorAction;
use App\Containers\Monitoring\Educator\UI\API\Requests\CreateEducatorRequest;
use App\Containers\Monitoring\Educator\UI\API\Transformers\EducatorTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class CreateEducatorController extends ApiController
{
    public function __invoke(CreateEducatorRequest $request, CreateEducatorAction $action): JsonResponse
    {
        $educator = $action->run($request);

        return Response::create($educator, EducatorTransformer::class)->created();
    }
}
