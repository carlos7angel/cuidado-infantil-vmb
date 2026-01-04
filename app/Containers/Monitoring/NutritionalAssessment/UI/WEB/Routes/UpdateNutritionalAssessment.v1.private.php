<?php

use App\Containers\Monitoring\NutritionalAssessment\UI\WEB\Controllers\UpdateNutritionalAssessmentController;
use Illuminate\Support\Facades\Route;

Route::patch('nutritional-assessments/{id}', UpdateNutritionalAssessmentController::class)
    ->middleware(['auth:web']);

