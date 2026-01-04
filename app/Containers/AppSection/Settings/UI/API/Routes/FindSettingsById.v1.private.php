<?php

/**
 * @apiGroup           Settings
 * @apiName            FindById
 *
 * @api                {GET} /v1/settings/:id Invoke
 * @apiDescription     Endpoint description here...
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated ['permissions' => '', 'roles' => '']
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} parameters here...
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     // Insert the response of the request here...
 * }
 */

use App\Containers\AppSection\Settings\UI\API\Controllers\FindSettingsByIdController;
use Illuminate\Support\Facades\Route;

Route::get('settings/{id}', FindSettingsByIdController::class)
    ->middleware(['auth:api']);

