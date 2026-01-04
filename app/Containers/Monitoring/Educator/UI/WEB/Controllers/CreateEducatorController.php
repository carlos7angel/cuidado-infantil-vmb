<?php

namespace App\Containers\Monitoring\Educator\UI\WEB\Controllers;

use App\Containers\Monitoring\Educator\UI\WEB\Requests\CreateEducatorRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\View\View;

final class CreateEducatorController extends WebController
{
    public function __invoke(CreateEducatorRequest $request): View
    {
        return view('placeholder');
    }
}
