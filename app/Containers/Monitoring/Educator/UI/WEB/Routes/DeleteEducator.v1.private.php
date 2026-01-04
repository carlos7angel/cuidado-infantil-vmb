<?php

use App\Containers\Monitoring\Educator\UI\WEB\Controllers\DeleteEducatorController;
use Illuminate\Support\Facades\Route;

Route::delete('educators/{id}', DeleteEducatorController::class)
    ->middleware(['auth:web']);

