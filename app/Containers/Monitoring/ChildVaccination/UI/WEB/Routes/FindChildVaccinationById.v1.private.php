<?php

use App\Containers\Monitoring\ChildVaccination\UI\WEB\Controllers\FindChildVaccinationByIdController;
use Illuminate\Support\Facades\Route;

Route::get('child-vaccinations/{id}', FindChildVaccinationByIdController::class)
    ->middleware(['auth:web']);

