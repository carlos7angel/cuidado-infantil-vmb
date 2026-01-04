<?php

use App\Containers\AppSection\Settings\UI\WEB\Controllers\DeleteSettingsController;
use Illuminate\Support\Facades\Route;

Route::delete('settings/{id}', DeleteSettingsController::class)
    ->middleware(['auth:web']);

