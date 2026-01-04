<?php

namespace App\Containers\AppSection\Settings\UI\WEB\Controllers;

use App\Containers\AppSection\Settings\Actions\CreateSettingsAction;
use App\Containers\AppSection\Settings\UI\WEB\Requests\StoreSettingsRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class StoreSettingsController extends WebController
{
    public function __invoke(StoreSettingsRequest $request, CreateSettingsAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
