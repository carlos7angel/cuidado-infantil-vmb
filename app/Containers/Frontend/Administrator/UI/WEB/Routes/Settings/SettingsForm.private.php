<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/ajustes-configuracion', [SettingsController::class, 'form'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.settings.form')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

