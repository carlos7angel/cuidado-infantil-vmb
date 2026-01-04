<?php

namespace App\Containers\AppSection\File\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\File\Actions\DeleteFileAction;
use App\Containers\AppSection\File\UI\API\Requests\DeleteFileRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class DeleteFileController extends ApiController
{
    public function __invoke(DeleteFileRequest $request, DeleteFileAction $action): JsonResponse
    {
        $action->run($request);

        return Response::noContent();
    }
}
