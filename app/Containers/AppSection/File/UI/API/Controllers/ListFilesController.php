<?php

namespace App\Containers\AppSection\File\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\File\Actions\ListFilesAction;
use App\Containers\AppSection\File\UI\API\Requests\ListFilesRequest;
use App\Containers\AppSection\File\UI\API\Transformers\FileTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class ListFilesController extends ApiController
{
    public function __invoke(ListFilesRequest $request, ListFilesAction $action): JsonResponse
    {
        $files = $action->run($request);

        return Response::create($files, FileTransformer::class)->ok();
    }
}
