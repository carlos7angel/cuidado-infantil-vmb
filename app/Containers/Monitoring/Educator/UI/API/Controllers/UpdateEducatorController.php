<?php

namespace App\Containers\Monitoring\Educator\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\Educator\Actions\UpdateEducatorAction;
use App\Containers\Monitoring\Educator\UI\API\Requests\UpdateEducatorRequest;
use App\Containers\Monitoring\Educator\UI\API\Transformers\EducatorTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class UpdateEducatorController extends ApiController
{
    public function __invoke(UpdateEducatorRequest $request, UpdateEducatorAction $action): JsonResponse
    {
        $educator = $action->run($request);

        return Response::create($educator, EducatorTransformer::class)->ok();
    }
}
