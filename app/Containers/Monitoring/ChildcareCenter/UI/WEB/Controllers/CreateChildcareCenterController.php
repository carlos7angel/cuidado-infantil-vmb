<?php

namespace App\Containers\Monitoring\ChildcareCenter\UI\WEB\Controllers;

use App\Containers\Monitoring\ChildcareCenter\UI\WEB\Requests\CreateChildcareCenterRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\View\View;

final class CreateChildcareCenterController extends WebController
{
    public function __invoke(CreateChildcareCenterRequest $request): View
    {
        return view('placeholder');
    }
}
