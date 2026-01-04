<?php

namespace App\Containers\Monitoring\Child\UI\WEB\Controllers;

use App\Containers\Monitoring\Child\UI\WEB\Requests\EditChildRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\View\View;

final class EditChildController extends WebController
{
    public function __invoke(EditChildRequest $request): View
    {
        return view('placeholder');
    }
}
