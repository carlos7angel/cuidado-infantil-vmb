<?php

use App\Containers\Monitoring\ChildDevelopment\UI\WEB\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::patch('child-developments/{id}', [Controller::class, 'update'])
    ->middleware(['auth:web']);

