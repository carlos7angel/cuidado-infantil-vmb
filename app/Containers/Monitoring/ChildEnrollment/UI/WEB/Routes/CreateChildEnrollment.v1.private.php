<?php

use App\Containers\Monitoring\ChildEnrollment\UI\WEB\Controllers\CreateChildEnrollmentController;
use Illuminate\Support\Facades\Route;

Route::get('child-enrollments/create', CreateChildEnrollmentController::class)
    ->middleware(['auth:web']);

