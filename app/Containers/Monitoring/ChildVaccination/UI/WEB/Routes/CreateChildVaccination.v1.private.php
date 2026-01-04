<?php

use App\Containers\Monitoring\ChildVaccination\UI\WEB\Controllers\CreateChildVaccinationController;
use Illuminate\Support\Facades\Route;

Route::get('child-vaccinations/create', CreateChildVaccinationController::class)
    ->middleware(['auth:web']);

