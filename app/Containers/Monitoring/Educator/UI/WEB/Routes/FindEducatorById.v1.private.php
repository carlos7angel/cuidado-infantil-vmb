<?php

use App\Containers\Monitoring\Educator\UI\WEB\Controllers\FindEducatorByIdController;
use Illuminate\Support\Facades\Route;

Route::get('educators/{id}', FindEducatorByIdController::class)
    ->middleware(['auth:web']);

