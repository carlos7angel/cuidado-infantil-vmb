<?php

use App\Containers\Monitoring\NutritionalAssessment\UI\WEB\Controllers\FindNutritionalAssessmentByIdController;
use Illuminate\Support\Facades\Route;

Route::get('nutritional-assessments/{id}', FindNutritionalAssessmentByIdController::class)
    ->middleware(['auth:web']);

