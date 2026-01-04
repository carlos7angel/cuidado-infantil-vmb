<?php

namespace App\Containers\AppSection\File\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\File\Actions\UpdateFileAction;
use App\Containers\AppSection\File\UI\API\Requests\UpdateFileRequest;
use App\Containers\AppSection\File\UI\API\Transformers\FileTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class UpdateFileController extends ApiController
{
    public function __invoke(UpdateFileRequest $request, UpdateFileAction $action): JsonResponse
    {
        $file = $action->run($request);

        return Response::create($file, FileTransformer::class)->ok();
    }
}
