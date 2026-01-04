<?php

namespace App\Containers\Monitoring\Child\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\Child\Actions\ListChildrenAction;
use App\Containers\Monitoring\Child\UI\API\Requests\ListChildrenRequest;
use App\Containers\Monitoring\Child\UI\API\Transformers\ChildListTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class ListChildrenController extends ApiController
{
    public function __invoke(ListChildrenRequest $request, ListChildrenAction $action): JsonResponse
    {
        $children = $action->run($request);

        return Response::create($children, ChildListTransformer::class)->ok();
    }
}
