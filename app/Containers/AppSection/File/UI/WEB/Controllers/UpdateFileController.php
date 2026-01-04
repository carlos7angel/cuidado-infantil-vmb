<?php

namespace App\Containers\AppSection\File\UI\WEB\Controllers;

use App\Containers\AppSection\File\Actions\UpdateFileAction;
use App\Containers\AppSection\File\UI\WEB\Requests\UpdateFileRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class UpdateFileController extends WebController
{
    public function __invoke(UpdateFileRequest $request, UpdateFileAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
