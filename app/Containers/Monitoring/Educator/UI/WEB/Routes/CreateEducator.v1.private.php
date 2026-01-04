<?php

use App\Containers\Monitoring\Educator\UI\WEB\Controllers\CreateEducatorController;
use Illuminate\Support\Facades\Route;

Route::get('educators/create', CreateEducatorController::class)
    ->middleware(['auth:web']);

