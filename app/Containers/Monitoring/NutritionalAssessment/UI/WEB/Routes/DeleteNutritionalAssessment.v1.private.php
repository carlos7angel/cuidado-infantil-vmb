<?php

use App\Containers\Monitoring\NutritionalAssessment\UI\WEB\Controllers\DeleteNutritionalAssessmentController;
use Illuminate\Support\Facades\Route;

Route::delete('nutritional-assessments/{id}', DeleteNutritionalAssessmentController::class)
    ->middleware(['auth:web']);

