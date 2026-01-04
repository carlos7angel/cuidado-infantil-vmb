<?php

namespace App\Containers\AppSection\Settings\UI\WEB\Controllers;

use App\Containers\AppSection\Settings\Actions\ListSettingsAction;
use App\Containers\AppSection\Settings\UI\WEB\Requests\ListSettingsRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class ListSettingsController extends WebController
{
    public function __invoke(ListSettingsRequest $request, ListSettingsAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
