<?php

use App\Containers\Monitoring\Child\UI\WEB\Controllers\FindChildByIdController;
use Illuminate\Support\Facades\Route;

Route::get('children/{id}', FindChildByIdController::class)
    ->middleware(['auth:web']);

