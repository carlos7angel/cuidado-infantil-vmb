<?php

use App\Containers\Monitoring\Educator\UI\WEB\Controllers\StoreEducatorController;
use Illuminate\Support\Facades\Route;

Route::post('educators/store', StoreEducatorController::class)
    ->middleware(['auth:web']);

