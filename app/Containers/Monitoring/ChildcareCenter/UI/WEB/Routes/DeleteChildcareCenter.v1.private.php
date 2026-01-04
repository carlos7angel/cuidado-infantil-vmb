<?php

use App\Containers\Monitoring\ChildcareCenter\UI\WEB\Controllers\DeleteChildcareCenterController;
use Illuminate\Support\Facades\Route;

Route::delete('childcare-centers/{id}', DeleteChildcareCenterController::class)
    ->middleware(['auth:web']);

