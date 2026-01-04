<?php

use App\Containers\Monitoring\ChildVaccination\UI\WEB\Controllers\DeleteChildVaccinationController;
use Illuminate\Support\Facades\Route;

Route::delete('child-vaccinations/{id}', DeleteChildVaccinationController::class)
    ->middleware(['auth:web']);

