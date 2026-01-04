<?php

namespace App\Containers\AppSection\File\UI\WEB\Controllers;

use App\Containers\AppSection\File\Actions\DeleteFileAction;
use App\Containers\AppSection\File\UI\WEB\Requests\DeleteFileRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class DeleteFileController extends WebController
{
    public function __invoke(DeleteFileRequest $request, DeleteFileAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
