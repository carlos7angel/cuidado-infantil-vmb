<?php

namespace App\Containers\AppSection\Settings\UI\WEB\Controllers;

use App\Containers\AppSection\Settings\Actions\DeleteSettingsAction;
use App\Containers\AppSection\Settings\UI\WEB\Requests\DeleteSettingsRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class DeleteSettingsController extends WebController
{
    public function __invoke(DeleteSettingsRequest $request, DeleteSettingsAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
