<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Route::get(config('app.admin_prefix'), function () {
    return redirect()->route('admin.dashboard');
});
