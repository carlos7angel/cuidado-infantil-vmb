<?php

use App\Containers\Monitoring\NutritionalAssessment\UI\WEB\Controllers\ListNutritionalAssessmentsController;
use Illuminate\Support\Facades\Route;

Route::get('nutritional-assessments', ListNutritionalAssessmentsController::class)
    ->middleware(['auth:web']);

