<?php

use App\Containers\Monitoring\Child\UI\WEB\Controllers\CreateChildController;
use Illuminate\Support\Facades\Route;

Route::get('children/create', CreateChildController::class)
    ->middleware(['auth:web']);

