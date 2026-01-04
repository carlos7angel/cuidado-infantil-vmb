<?php

use App\Containers\Monitoring\Child\UI\WEB\Controllers\StoreChildController;
use Illuminate\Support\Facades\Route;

Route::post('children/store', StoreChildController::class)
    ->middleware(['auth:web']);

