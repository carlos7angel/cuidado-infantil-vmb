<?php

namespace App\Containers\Monitoring\ChildcareCenter\UI\WEB\Controllers;

use App\Containers\Monitoring\ChildcareCenter\UI\WEB\Requests\EditChildcareCenterRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\View\View;

final class EditChildcareCenterController extends WebController
{
    public function __invoke(EditChildcareCenterRequest $request): View
    {
        return view('placeholder');
    }
}
