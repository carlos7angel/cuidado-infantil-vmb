<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\ImportChildrenController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('app.admin_prefix'),
    'middleware' => ['auth:web'],
    'domain' => parse_url(config('app.admin_url'), PHP_URL_HOST),
], function () {
    Route::get('/infantes/importar', [ImportChildrenController::class, 'showImportForm'])
        ->name('admin.child.import.form');

    Route::get('/infantes/importar/salas/{childcare_center_id}', [ImportChildrenController::class, 'getRoomsByCenter'])
        ->name('admin.child.import.get_rooms');

    Route::post('/infantes/importar/preview', [ImportChildrenController::class, 'preview'])
        ->name('admin.child.import.preview');

    Route::post('/infantes/importar/store', [ImportChildrenController::class, 'import'])
        ->name('admin.child.import.store');
});
