<?php

namespace App\Containers\Monitoring\Child\UI\WEB\Controllers;

use App\Containers\Monitoring\Child\Actions\FindChildByIdAction;
use App\Containers\Monitoring\Child\UI\WEB\Requests\FindChildByIdRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class FindChildByIdController extends WebController
{
    public function __invoke(FindChildByIdRequest $request, FindChildByIdAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
