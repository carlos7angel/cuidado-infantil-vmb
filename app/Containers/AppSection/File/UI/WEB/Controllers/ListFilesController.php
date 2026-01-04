<?php

namespace App\Containers\AppSection\File\UI\WEB\Controllers;

use App\Containers\AppSection\File\Actions\ListFilesAction;
use App\Containers\AppSection\File\UI\WEB\Requests\ListFilesRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class ListFilesController extends WebController
{
    public function __invoke(ListFilesRequest $request, ListFilesAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
