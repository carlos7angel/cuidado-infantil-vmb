<?php

use App\Containers\Monitoring\ChildcareCenter\UI\WEB\Controllers\FindChildcareCenterByIdController;
use Illuminate\Support\Facades\Route;

Route::get('childcare-centers/{id}', FindChildcareCenterByIdController::class)
    ->middleware(['auth:web']);

