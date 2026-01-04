<?php

use App\Containers\Monitoring\Child\UI\WEB\Controllers\ListChildrenController;
use Illuminate\Support\Facades\Route;

Route::get('children', ListChildrenController::class)
    ->middleware(['auth:web']);

