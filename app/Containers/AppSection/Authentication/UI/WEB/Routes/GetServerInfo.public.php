<?php

/**
 * @apiGroup           Server
 * @apiName            GetServerInfo
 * @api                {get} /v1/server/info Get Server Info
 * @apiDescription     Returns basic server information for app connection.
 * @apiVersion         1.0.0
 * @apiPermission      none
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *   "host": "https://api.lapaz.cci.gob.bo",
 *   "status": "active",
 *   "api_version": "v1",
 *   "municipality": "La Paz",
 *   "department": "La Paz"
 * }
 */

use App\Containers\AppSection\Authentication\UI\WEB\Controllers\GetServerInfoController;
use Illuminate\Support\Facades\Route;

Route::get('/server/info', GetServerInfoController::class)
    ->middleware(['guest'])
    ->name('web.server.info')
    ->domain(parse_url(config('api.url'), PHP_URL_HOST));
