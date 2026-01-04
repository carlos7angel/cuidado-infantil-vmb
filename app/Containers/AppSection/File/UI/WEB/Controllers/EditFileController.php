<?php

namespace App\Containers\AppSection\File\UI\WEB\Controllers;

use App\Containers\AppSection\File\UI\WEB\Requests\EditFileRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\View\View;

final class EditFileController extends WebController
{
    public function __invoke(EditFileRequest $request): View
    {
        return view('placeholder');
    }
}
