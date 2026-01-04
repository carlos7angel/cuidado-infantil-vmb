<?php

namespace App\Containers\AppSection\File\UI\WEB\Controllers;

use App\Containers\AppSection\File\Actions\FindFileByIdAction;
use App\Containers\AppSection\File\UI\WEB\Requests\FindFileByIdRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class FindFileByIdController extends WebController
{
    public function __invoke(FindFileByIdRequest $request, FindFileByIdAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
