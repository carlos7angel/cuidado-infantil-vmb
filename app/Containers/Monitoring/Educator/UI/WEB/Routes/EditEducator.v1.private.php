<?php

use App\Containers\Monitoring\Educator\UI\WEB\Controllers\EditEducatorController;
use Illuminate\Support\Facades\Route;

Route::get('educators/{id}/edit', EditEducatorController::class)
    ->middleware(['auth:web']);

