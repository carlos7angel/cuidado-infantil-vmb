<?php

use App\Containers\Monitoring\Child\UI\WEB\Controllers\DeleteChildController;
use Illuminate\Support\Facades\Route;

Route::delete('children/{id}', DeleteChildController::class)
    ->middleware(['auth:web']);

