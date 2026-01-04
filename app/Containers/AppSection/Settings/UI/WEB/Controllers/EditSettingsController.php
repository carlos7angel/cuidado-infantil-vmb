<?php

namespace App\Containers\AppSection\Settings\UI\WEB\Controllers;

use App\Containers\AppSection\Settings\UI\WEB\Requests\EditSettingsRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\View\View;

final class EditSettingsController extends WebController
{
    public function __invoke(EditSettingsRequest $request): View
    {
        return view('placeholder');
    }
}
