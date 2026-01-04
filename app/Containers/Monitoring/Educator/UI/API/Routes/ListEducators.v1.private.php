<?php

/**
 * @apiGroup           Educator
 * @apiName            List
 *
 * @api                {GET} /v1/educators Invoke
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

use App\Containers\Monitoring\Educator\UI\API\Controllers\ListEducatorsController;
use Illuminate\Support\Facades\Route;

Route::get('educators', ListEducatorsController::class)
    ->middleware(['auth:api']);

