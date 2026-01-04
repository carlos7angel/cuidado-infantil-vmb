<?php

/**
 * @apiGroup           Educator
 * @apiName            Create
 *
 * @api                {POST} /v1/educators Invoke
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

use App\Containers\Monitoring\Educator\UI\API\Controllers\CreateEducatorController;
use Illuminate\Support\Facades\Route;

Route::post('educators', CreateEducatorController::class)
    ->middleware(['auth:api']);

