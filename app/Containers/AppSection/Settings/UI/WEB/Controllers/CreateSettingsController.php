<?php

namespace App\Containers\AppSection\Settings\UI\WEB\Controllers;

use App\Containers\AppSection\Settings\UI\WEB\Requests\CreateSettingsRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\View\View;

final class CreateSettingsController extends WebController
{
    public function __invoke(CreateSettingsRequest $request): View
    {
        return view('placeholder');
    }
}
