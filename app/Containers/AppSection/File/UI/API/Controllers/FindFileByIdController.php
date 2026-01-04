<?php

namespace App\Containers\AppSection\File\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\File\Actions\FindFileByIdAction;
use App\Containers\AppSection\File\UI\API\Requests\FindFileByIdRequest;
use App\Containers\AppSection\File\UI\API\Transformers\FileTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class FindFileByIdController extends ApiController
{
    public function __invoke(FindFileByIdRequest $request, FindFileByIdAction $action): JsonResponse
    {
        $file = $action->run($request);

        return Response::create($file, FileTransformer::class)->ok();
    }
}
