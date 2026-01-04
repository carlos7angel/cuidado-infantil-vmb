<?php

/**
 * @apiGroup           ActivityLog
 * @apiName            Delete
 *
 * @api                {DELETE} /v1/activity-logs/:id Invoke
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

use App\Containers\AppSection\ActivityLog\UI\API\Controllers\DeleteActivityLogController;
use Illuminate\Support\Facades\Route;

Route::delete('activity-logs/{id}', DeleteActivityLogController::class)
    ->middleware(['auth:api']);

