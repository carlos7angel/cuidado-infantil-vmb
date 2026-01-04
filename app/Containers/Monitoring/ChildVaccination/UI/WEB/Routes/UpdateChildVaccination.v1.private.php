<?php

use App\Containers\Monitoring\ChildVaccination\UI\WEB\Controllers\UpdateChildVaccinationController;
use Illuminate\Support\Facades\Route;

Route::patch('child-vaccinations/{id}', UpdateChildVaccinationController::class)
    ->middleware(['auth:web']);

