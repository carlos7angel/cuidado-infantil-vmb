<?php

use App\Containers\Monitoring\ChildEnrollment\UI\WEB\Controllers\ListChildEnrollmentsController;
use Illuminate\Support\Facades\Route;

Route::get('child-enrollments', ListChildEnrollmentsController::class)
    ->middleware(['auth:web']);

