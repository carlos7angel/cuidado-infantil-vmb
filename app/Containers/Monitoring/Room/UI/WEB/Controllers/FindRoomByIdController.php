<?php

namespace App\Containers\Monitoring\Room\UI\WEB\Controllers;

use App\Containers\Monitoring\Room\Actions\FindRoomByIdAction;
use App\Containers\Monitoring\Room\UI\WEB\Requests\FindRoomByIdRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class FindRoomByIdController extends WebController
{
    public function __invoke(FindRoomByIdRequest $request, FindRoomByIdAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
