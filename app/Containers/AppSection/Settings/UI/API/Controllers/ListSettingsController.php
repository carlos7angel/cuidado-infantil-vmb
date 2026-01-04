<?php

namespace App\Containers\AppSection\Settings\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\Settings\Actions\ListSettingsAction;
use App\Containers\AppSection\Settings\UI\API\Requests\ListSettingsRequest;
use App\Containers\AppSection\Settings\UI\API\Transformers\SettingsTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class ListSettingsController extends ApiController
{
    public function __invoke(ListSettingsRequest $request, ListSettingsAction $action): JsonResponse
    {
        $settings = $action->run($request);

        return Response::create($settings, SettingsTransformer::class)->ok();
    }
}
