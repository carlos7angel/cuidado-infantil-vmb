<?php

use App\Containers\Monitoring\Educator\UI\WEB\Controllers\UpdateEducatorController;
use Illuminate\Support\Facades\Route;

Route::patch('educators/{id}', UpdateEducatorController::class)
    ->middleware(['auth:web']);

