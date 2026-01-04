<?php

namespace App\Containers\Monitoring\Educator\UI\WEB\Controllers;

use App\Containers\Monitoring\Educator\Actions\DeleteEducatorAction;
use App\Containers\Monitoring\Educator\UI\WEB\Requests\DeleteEducatorRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class DeleteEducatorController extends WebController
{
    public function __invoke(DeleteEducatorRequest $request, DeleteEducatorAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
