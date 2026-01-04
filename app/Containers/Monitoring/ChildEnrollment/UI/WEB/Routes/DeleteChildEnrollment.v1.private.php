<?php

use App\Containers\Monitoring\ChildEnrollment\UI\WEB\Controllers\DeleteChildEnrollmentController;
use Illuminate\Support\Facades\Route;

Route::delete('child-enrollments/{id}', DeleteChildEnrollmentController::class)
    ->middleware(['auth:web']);

