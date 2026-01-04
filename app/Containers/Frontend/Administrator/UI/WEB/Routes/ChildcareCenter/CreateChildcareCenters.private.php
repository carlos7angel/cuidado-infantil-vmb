<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\ChildcareCenterController;
use Illuminate\Support\Facades\Route;

Route::get('/centros/nuevo', [ChildcareCenterController::class, 'form_create'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.childcare_center.form_create')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

