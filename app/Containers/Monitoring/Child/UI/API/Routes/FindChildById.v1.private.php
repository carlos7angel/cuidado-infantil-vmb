<?php

/**
 * @apiGroup           Child
 * @apiName            FindById
 *
 * @api                {GET} /v1/children/:id Invoke
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

use App\Containers\Monitoring\Child\UI\API\Controllers\FindChildByIdController;
use Illuminate\Support\Facades\Route;

Route::get('children/{id}', FindChildByIdController::class)
    ->middleware(['auth:api']);

