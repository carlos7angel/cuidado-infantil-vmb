<?php

namespace App\Containers\Monitoring\Child\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\Child\Actions\CreateChildAction;
use App\Containers\Monitoring\Child\UI\API\Requests\CreateChildRequest;
use App\Containers\Monitoring\Child\UI\API\Transformers\ChildTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class CreateChildController extends ApiController
{
    public function __invoke(CreateChildRequest $request, CreateChildAction $action): JsonResponse
    {
        $child = $action->run($request);

        return Response::create($child, ChildTransformer::class)->created();
    }
}
