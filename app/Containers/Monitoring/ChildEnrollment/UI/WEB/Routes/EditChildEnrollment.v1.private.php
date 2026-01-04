<?php

use App\Containers\Monitoring\ChildEnrollment\UI\WEB\Controllers\EditChildEnrollmentController;
use Illuminate\Support\Facades\Route;

Route::get('child-enrollments/{id}/edit', EditChildEnrollmentController::class)
    ->middleware(['auth:web']);

