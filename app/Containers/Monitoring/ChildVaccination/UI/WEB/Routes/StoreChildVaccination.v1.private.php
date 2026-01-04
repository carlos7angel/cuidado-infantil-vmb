<?php

use App\Containers\Monitoring\ChildVaccination\UI\WEB\Controllers\StoreChildVaccinationController;
use Illuminate\Support\Facades\Route;

Route::post('child-vaccinations/store', StoreChildVaccinationController::class)
    ->middleware(['auth:web']);

