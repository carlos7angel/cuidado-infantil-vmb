<?php

use App\Containers\Monitoring\ChildEnrollment\UI\WEB\Controllers\FindChildEnrollmentByIdController;
use Illuminate\Support\Facades\Route;

Route::get('child-enrollments/{id}', FindChildEnrollmentByIdController::class)
    ->middleware(['auth:web']);

