<?php

use App\Containers\Monitoring\NutritionalAssessment\UI\WEB\Controllers\StoreNutritionalAssessmentController;
use Illuminate\Support\Facades\Route;

Route::post('nutritional-assessments/store', StoreNutritionalAssessmentController::class)
    ->middleware(['auth:web']);

