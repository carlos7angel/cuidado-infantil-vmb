<?php

use App\Containers\Monitoring\ChildEnrollment\UI\WEB\Controllers\StoreChildEnrollmentController;
use Illuminate\Support\Facades\Route;

Route::post('child-enrollments/store', StoreChildEnrollmentController::class)
    ->middleware(['auth:web']);

