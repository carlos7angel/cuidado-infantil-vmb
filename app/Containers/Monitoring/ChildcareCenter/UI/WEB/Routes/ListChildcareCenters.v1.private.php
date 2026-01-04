<?php

use App\Containers\Monitoring\ChildcareCenter\UI\WEB\Controllers\ListChildcareCentersController;
use Illuminate\Support\Facades\Route;

Route::get('childcare-centers', ListChildcareCentersController::class)
    ->middleware(['auth:web']);

