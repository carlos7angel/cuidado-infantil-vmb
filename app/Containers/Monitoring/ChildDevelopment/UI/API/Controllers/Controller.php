<?php

namespace App\Containers\Monitoring\ChildDevelopment\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\ChildDevelopment\Actions\CreateChildDevelopmentAction;
use App\Containers\Monitoring\ChildDevelopment\Actions\DeleteChildDevelopmentAction;
use App\Containers\Monitoring\ChildDevelopment\Actions\FindChildDevelopmentByIdAction;
use App\Containers\Monitoring\ChildDevelopment\Actions\ListChildDevelopmentsAction;
use App\Containers\Monitoring\ChildDevelopment\Actions\UpdateChildDevelopmentAction;
use App\Containers\Monitoring\ChildDevelopment\UI\API\Requests\CreateChildDevelopmentRequest;
use App\Containers\Monitoring\ChildDevelopment\UI\API\Requests\DeleteChildDevelopmentRequest;
use App\Containers\Monitoring\ChildDevelopment\UI\API\Requests\FindChildDevelopmentByIdRequest;
use App\Containers\Monitoring\ChildDevelopment\UI\API\Requests\ListChildDevelopmentsRequest;
use App\Containers\Monitoring\ChildDevelopment\UI\API\Requests\UpdateChildDevelopmentRequest;
use App\Containers\Monitoring\ChildDevelopment\UI\API\Transformers\ChildDevelopmentTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class Controller extends ApiController
{
    public function create(CreateChildDevelopmentRequest $request, CreateChildDevelopmentAction $action): JsonResponse
    {
        $evaluation = $action->run($request);

        return Response::create($evaluation, ChildDevelopmentTransformer::class)->created();
    }

    public function findById(FindChildDevelopmentByIdRequest $request, FindChildDevelopmentByIdAction $action): JsonResponse
    {
        $childDevelopment = $action->run($request);

        return Response::create($childDevelopment, ChildDevelopmentTransformer::class)->ok();
    }

    public function list(ListChildDevelopmentsRequest $request, ListChildDevelopmentsAction $action): JsonResponse
    {
        $childDevelopments = $action->run($request);

        return Response::create($childDevelopments, ChildDevelopmentTransformer::class)->ok();
    }

    public function update(UpdateChildDevelopmentRequest $request, UpdateChildDevelopmentAction $action): JsonResponse
    {
        $childDevelopment = $action->run($request);

        return Response::create($childDevelopment, ChildDevelopmentTransformer::class)->ok();
    }

    public function delete(DeleteChildDevelopmentRequest $request, DeleteChildDevelopmentAction $action): JsonResponse
    {
        $action->run($request);

        return Response::noContent();
    }
}
