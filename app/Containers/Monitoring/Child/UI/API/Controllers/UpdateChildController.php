<?php

namespace App\Containers\Monitoring\Child\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\Child\Actions\UpdateChildAction;
use App\Containers\Monitoring\Child\UI\API\Requests\UpdateChildRequest;
use App\Containers\Monitoring\Child\UI\API\Transformers\ChildTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class UpdateChildController extends ApiController
{
    public function __invoke(UpdateChildRequest $request, UpdateChildAction $action): JsonResponse
    {
        // dd($request->all());
        $child = $action->run($request);

        return Response::create($child, ChildTransformer::class)->ok();
    }
}
