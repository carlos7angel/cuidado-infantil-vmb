<?php

/**
 * @apiGroup           ChildcareCenter
 * @apiName            Update
 *
 * @api                {PATCH} /v1/childcare-centers/:id Invoke
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

use App\Containers\Monitoring\ChildcareCenter\UI\API\Controllers\UpdateChildcareCenterController;
use Illuminate\Support\Facades\Route;

Route::patch('childcare-centers/{id}', UpdateChildcareCenterController::class)
    ->middleware(['auth:api']);

