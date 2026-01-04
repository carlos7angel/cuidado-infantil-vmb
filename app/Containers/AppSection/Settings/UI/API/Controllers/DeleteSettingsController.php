<?php

namespace App\Containers\AppSection\Settings\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\Settings\Actions\DeleteSettingsAction;
use App\Containers\AppSection\Settings\UI\API\Requests\DeleteSettingsRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class DeleteSettingsController extends ApiController
{
    public function __invoke(DeleteSettingsRequest $request, DeleteSettingsAction $action): JsonResponse
    {
        $action->run($request);

        return Response::noContent();
    }
}
