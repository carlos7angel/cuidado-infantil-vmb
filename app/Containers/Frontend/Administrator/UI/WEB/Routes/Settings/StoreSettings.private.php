<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::post('/ajustes-configuracion/guardar', [SettingsController::class, 'store'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.settings.store')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

