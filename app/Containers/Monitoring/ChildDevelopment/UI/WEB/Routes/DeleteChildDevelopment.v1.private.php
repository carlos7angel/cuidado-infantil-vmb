<?php

use App\Containers\Monitoring\ChildDevelopment\UI\WEB\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::delete('child-developments/{id}', [Controller::class, 'destroy'])
    ->middleware(['auth:web']);

