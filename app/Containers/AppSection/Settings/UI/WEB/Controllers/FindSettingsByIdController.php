<?php

namespace App\Containers\AppSection\Settings\UI\WEB\Controllers;

use App\Containers\AppSection\Settings\Actions\FindSettingsByIdAction;
use App\Containers\AppSection\Settings\UI\WEB\Requests\FindSettingsByIdRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class FindSettingsByIdController extends WebController
{
    public function __invoke(FindSettingsByIdRequest $request, FindSettingsByIdAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
