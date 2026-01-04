<?php

/**
 * @apiGroup           ChildcareCenter
 * @apiName            List
 *
 * @api                {GET} /v1/childcare-centers Invoke
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

use App\Containers\Monitoring\ChildcareCenter\UI\API\Controllers\ListChildcareCentersController;
use Illuminate\Support\Facades\Route;

Route::get('childcare-centers', ListChildcareCentersController::class)
    ->middleware(['auth:api']);

