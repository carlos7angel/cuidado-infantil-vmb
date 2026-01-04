<?php

use App\Containers\Monitoring\ChildcareCenter\UI\WEB\Controllers\CreateChildcareCenterController;
use Illuminate\Support\Facades\Route;

Route::get('childcare-centers/create', CreateChildcareCenterController::class)
    ->middleware(['auth:web']);

