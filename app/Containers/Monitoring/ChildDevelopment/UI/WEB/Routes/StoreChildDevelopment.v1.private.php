<?php

use App\Containers\Monitoring\ChildDevelopment\UI\WEB\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::post('child-developments/store', [Controller::class, 'store'])
    ->middleware(['auth:web']);

