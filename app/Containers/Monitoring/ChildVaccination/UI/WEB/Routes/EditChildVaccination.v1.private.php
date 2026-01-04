<?php

use App\Containers\Monitoring\ChildVaccination\UI\WEB\Controllers\EditChildVaccinationController;
use Illuminate\Support\Facades\Route;

Route::get('child-vaccinations/{id}/edit', EditChildVaccinationController::class)
    ->middleware(['auth:web']);

