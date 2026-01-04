<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\ChildcareCenterController;
use Illuminate\Support\Facades\Route;

Route::get('/centros/editar/{id}', [ChildcareCenterController::class, 'form_edit'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.childcare_center.form_edit')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

