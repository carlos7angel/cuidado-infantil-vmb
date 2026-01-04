<?php

namespace App\Containers\AppSection\Settings\UI\WEB\Controllers;

use App\Containers\AppSection\Settings\Actions\UpdateSettingsAction;
use App\Containers\AppSection\Settings\UI\WEB\Requests\UpdateSettingsRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class UpdateSettingsController extends WebController
{
    public function __invoke(UpdateSettingsRequest $request, UpdateSettingsAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
