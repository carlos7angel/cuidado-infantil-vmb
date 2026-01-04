<?php

use App\Containers\Monitoring\ChildVaccination\UI\WEB\Controllers\ListChildVaccinationsController;
use Illuminate\Support\Facades\Route;

Route::get('child-vaccinations', ListChildVaccinationsController::class)
    ->middleware(['auth:web']);

