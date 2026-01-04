<?php

namespace App\Containers\AppSection\File\UI\WEB\Controllers;

use App\Containers\AppSection\File\Actions\CreateFileAction;
use App\Containers\AppSection\File\UI\WEB\Requests\StoreFileRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class StoreFileController extends WebController
{
    public function __invoke(StoreFileRequest $request, CreateFileAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
