<?php

use App\Containers\AppSection\Settings\UI\WEB\Controllers\EditSettingsController;
use Illuminate\Support\Facades\Route;

Route::get('settings/{id}/edit', EditSettingsController::class)
    ->middleware(['auth:web']);

