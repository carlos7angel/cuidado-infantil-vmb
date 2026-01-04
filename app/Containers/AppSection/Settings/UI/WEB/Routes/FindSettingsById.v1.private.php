<?php

use App\Containers\AppSection\Settings\UI\WEB\Controllers\FindSettingsByIdController;
use Illuminate\Support\Facades\Route;

Route::get('settings/{id}', FindSettingsByIdController::class)
    ->middleware(['auth:web']);

