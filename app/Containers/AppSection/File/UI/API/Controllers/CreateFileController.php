<?php

namespace App\Containers\AppSection\File\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\File\Actions\CreateFileAction;
use App\Containers\AppSection\File\UI\API\Requests\CreateFileRequest;
use App\Containers\AppSection\File\UI\API\Transformers\FileTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class CreateFileController extends ApiController
{
    public function __invoke(CreateFileRequest $request, CreateFileAction $action): JsonResponse
    {
        $file = $action->run($request);

        return Response::create($file, FileTransformer::class)->created();
    }
}
