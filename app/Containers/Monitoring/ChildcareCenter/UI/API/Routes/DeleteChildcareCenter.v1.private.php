<?php

/**
 * @apiGroup           ChildcareCenter
 * @apiName            Delete
 *
 * @api                {DELETE} /v1/childcare-centers/:id Invoke
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

use App\Containers\Monitoring\ChildcareCenter\UI\API\Controllers\DeleteChildcareCenterController;
use Illuminate\Support\Facades\Route;

Route::delete('childcare-centers/{id}', DeleteChildcareCenterController::class)
    ->middleware(['auth:api']);

