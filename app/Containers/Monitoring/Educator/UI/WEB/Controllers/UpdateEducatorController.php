<?php

namespace App\Containers\Monitoring\Educator\UI\WEB\Controllers;

use App\Containers\Monitoring\Educator\Actions\UpdateEducatorAction;
use App\Containers\Monitoring\Educator\UI\WEB\Requests\UpdateEducatorRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class UpdateEducatorController extends WebController
{
    public function __invoke(UpdateEducatorRequest $request, UpdateEducatorAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
