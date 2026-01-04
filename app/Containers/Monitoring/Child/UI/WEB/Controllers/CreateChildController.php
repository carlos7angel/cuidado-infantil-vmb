<?php

namespace App\Containers\Monitoring\Child\UI\WEB\Controllers;

use App\Containers\Monitoring\Child\UI\WEB\Requests\CreateChildRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\View\View;

final class CreateChildController extends WebController
{
    public function __invoke(CreateChildRequest $request): View
    {
        return view('placeholder');
    }
}
