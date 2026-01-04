<?php

namespace App\Containers\Monitoring\Educator\UI\WEB\Controllers;

use App\Containers\Monitoring\Educator\UI\WEB\Requests\EditEducatorRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\View\View;

final class EditEducatorController extends WebController
{
    public function __invoke(EditEducatorRequest $request): View
    {
        return view('placeholder');
    }
}
