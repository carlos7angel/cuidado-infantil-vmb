<?php

use App\Containers\AppSection\Settings\UI\WEB\Controllers\UpdateSettingsController;
use Illuminate\Support\Facades\Route;

Route::patch('settings/{id}', UpdateSettingsController::class)
    ->middleware(['auth:web']);

