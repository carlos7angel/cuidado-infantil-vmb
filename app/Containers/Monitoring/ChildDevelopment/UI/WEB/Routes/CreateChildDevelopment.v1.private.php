<?php

use App\Containers\Monitoring\ChildDevelopment\UI\WEB\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::get('child-developments/create', [Controller::class, 'create'])
    ->middleware(['auth:web']);

