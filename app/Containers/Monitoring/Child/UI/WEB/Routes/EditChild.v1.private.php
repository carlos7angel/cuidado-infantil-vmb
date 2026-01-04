<?php

use App\Containers\Monitoring\Child\UI\WEB\Controllers\EditChildController;
use Illuminate\Support\Facades\Route;

Route::get('children/{id}/edit', EditChildController::class)
    ->middleware(['auth:web']);

