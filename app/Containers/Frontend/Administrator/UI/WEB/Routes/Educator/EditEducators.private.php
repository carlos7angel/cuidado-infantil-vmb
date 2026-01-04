<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\EducatorController;
use Illuminate\Support\Facades\Route;

Route::get('/educadores/editar/{id}', [EducatorController::class, 'form_edit'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.educator.form_edit')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

