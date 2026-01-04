<?php

namespace App\Containers\AppSection\Settings\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\Settings\Actions\FindSettingsByIdAction;
use App\Containers\AppSection\Settings\UI\API\Requests\FindSettingsByIdRequest;
use App\Containers\AppSection\Settings\UI\API\Transformers\SettingsTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class FindSettingsByIdController extends ApiController
{
    public function __invoke(FindSettingsByIdRequest $request, FindSettingsByIdAction $action): JsonResponse
    {
        $settings = $action->run($request);

        return Response::create($settings, SettingsTransformer::class)->ok();
    }
}
