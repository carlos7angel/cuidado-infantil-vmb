<?php

namespace App\Containers\AppSection\File\UI\WEB\Controllers;

use App\Containers\AppSection\File\UI\WEB\Requests\CreateFileRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\View\View;

final class CreateFileController extends WebController
{
    public function __invoke(CreateFileRequest $request): View
    {
        return view('placeholder');
    }
}
