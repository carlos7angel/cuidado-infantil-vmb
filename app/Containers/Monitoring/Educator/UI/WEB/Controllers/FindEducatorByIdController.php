<?php

namespace App\Containers\Monitoring\Educator\UI\WEB\Controllers;

use App\Containers\Monitoring\Educator\Actions\FindEducatorByIdAction;
use App\Containers\Monitoring\Educator\UI\WEB\Requests\FindEducatorByIdRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class FindEducatorByIdController extends WebController
{
    public function __invoke(FindEducatorByIdRequest $request, FindEducatorByIdAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
