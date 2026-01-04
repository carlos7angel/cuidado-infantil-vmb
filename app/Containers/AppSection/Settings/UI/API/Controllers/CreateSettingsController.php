<?php

namespace App\Containers\AppSection\Settings\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\Settings\Actions\CreateSettingsAction;
use App\Containers\AppSection\Settings\UI\API\Requests\CreateSettingsRequest;
use App\Containers\AppSection\Settings\UI\API\Transformers\SettingsTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class CreateSettingsController extends ApiController
{
    public function __invoke(CreateSettingsRequest $request, CreateSettingsAction $action): JsonResponse
    {
        $settings = $action->run($request);

        return Response::create($settings, SettingsTransformer::class)->created();
    }
}
