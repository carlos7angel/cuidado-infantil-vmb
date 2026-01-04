<?php

use App\Containers\AppSection\File\UI\WEB\Controllers\StoreFileController;
use Illuminate\Support\Facades\Route;

Route::post('files/store', StoreFileController::class)
    ->middleware(['auth:web']);

