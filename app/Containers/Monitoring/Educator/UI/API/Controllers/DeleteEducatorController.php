<?php

namespace App\Containers\Monitoring\Educator\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\Educator\Actions\DeleteEducatorAction;
use App\Containers\Monitoring\Educator\UI\API\Requests\DeleteEducatorRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class DeleteEducatorController extends ApiController
{
    public function __invoke(DeleteEducatorRequest $request, DeleteEducatorAction $action): JsonResponse
    {
        $action->run($request);

        return Response::noContent();
    }
}
