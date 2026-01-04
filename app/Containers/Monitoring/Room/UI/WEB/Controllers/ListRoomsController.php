<?php

namespace App\Containers\Monitoring\Room\UI\WEB\Controllers;

use App\Containers\Monitoring\Room\Actions\ListRoomsAction;
use App\Containers\Monitoring\Room\UI\WEB\Requests\ListRoomsRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class ListRoomsController extends WebController
{
    public function __invoke(ListRoomsRequest $request, ListRoomsAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
