<?php

use App\Containers\Monitoring\Educator\UI\WEB\Controllers\ListEducatorsController;
use Illuminate\Support\Facades\Route;

Route::get('educators', ListEducatorsController::class)
    ->middleware(['auth:web']);

