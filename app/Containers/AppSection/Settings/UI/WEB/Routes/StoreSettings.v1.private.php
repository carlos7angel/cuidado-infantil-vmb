<?php

use App\Containers\AppSection\Settings\UI\WEB\Controllers\StoreSettingsController;
use Illuminate\Support\Facades\Route;

Route::post('settings/store', StoreSettingsController::class)
    ->middleware(['auth:web']);

