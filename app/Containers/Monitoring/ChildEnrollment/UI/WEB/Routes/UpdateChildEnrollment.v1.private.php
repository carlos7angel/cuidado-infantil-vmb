<?php

use App\Containers\Monitoring\ChildEnrollment\UI\WEB\Controllers\UpdateChildEnrollmentController;
use Illuminate\Support\Facades\Route;

Route::patch('child-enrollments/{id}', UpdateChildEnrollmentController::class)
    ->middleware(['auth:web']);

