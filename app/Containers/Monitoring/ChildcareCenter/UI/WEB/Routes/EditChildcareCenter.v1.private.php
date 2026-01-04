<?php

use App\Containers\Monitoring\ChildcareCenter\UI\WEB\Controllers\EditChildcareCenterController;
use Illuminate\Support\Facades\Route;

Route::get('childcare-centers/{id}/edit', EditChildcareCenterController::class)
    ->middleware(['auth:web']);

