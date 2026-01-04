<?php

use App\Containers\Monitoring\ChildcareCenter\UI\WEB\Controllers\UpdateChildcareCenterController;
use Illuminate\Support\Facades\Route;

Route::patch('childcare-centers/{id}', UpdateChildcareCenterController::class)
    ->middleware(['auth:web']);

