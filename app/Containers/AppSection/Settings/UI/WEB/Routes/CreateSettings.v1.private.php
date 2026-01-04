<?php

use App\Containers\AppSection\Settings\UI\WEB\Controllers\CreateSettingsController;
use Illuminate\Support\Facades\Route;

Route::get('settings/create', CreateSettingsController::class)
    ->middleware(['auth:web']);

