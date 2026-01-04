<?php

use App\Containers\Monitoring\Child\UI\WEB\Controllers\UpdateChildController;
use Illuminate\Support\Facades\Route;

Route::patch('children/{id}', UpdateChildController::class)
    ->middleware(['auth:web']);

