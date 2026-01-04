<?php

use App\Containers\Monitoring\ChildcareCenter\UI\WEB\Controllers\StoreChildcareCenterController;
use Illuminate\Support\Facades\Route;

Route::post('childcare-centers/store', StoreChildcareCenterController::class)
    ->middleware(['auth:web']);

