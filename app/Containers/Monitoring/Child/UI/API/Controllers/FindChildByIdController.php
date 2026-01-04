<?php

namespace App\Containers\Monitoring\Child\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\Child\Actions\FindChildByIdAction;
use App\Containers\Monitoring\Child\UI\API\Requests\FindChildByIdRequest;
use App\Containers\Monitoring\Child\UI\API\Transformers\ChildTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class FindChildByIdController extends ApiController
{
    public function __invoke(FindChildByIdRequest $request, FindChildByIdAction $action): JsonResponse
    {
        $child = $action->run($request);

        return Response::create($child, ChildTransformer::class)->ok();
    }
}
