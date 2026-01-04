<?php

use App\Containers\Monitoring\NutritionalAssessment\UI\WEB\Controllers\CreateNutritionalAssessmentController;
use Illuminate\Support\Facades\Route;

Route::get('nutritional-assessments/create', CreateNutritionalAssessmentController::class)
    ->middleware(['auth:web']);

