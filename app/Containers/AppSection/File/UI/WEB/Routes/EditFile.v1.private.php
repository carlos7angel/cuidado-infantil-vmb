<?php

use App\Containers\AppSection\File\UI\WEB\Controllers\EditFileController;
use Illuminate\Support\Facades\Route;

Route::get('files/{id}/edit', EditFileController::class)
    ->middleware(['auth:web']);

