<?php

/**
 * @apiGroup           Attendance
 * @apiName            Update
 *
 * @api                {PATCH} /v1/attendances/:id Invoke
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

use App\Containers\Monitoring\Attendance\UI\API\Controllers\UpdateAttendanceController;
use Illuminate\Support\Facades\Route;

Route::patch('attendances/{id}', UpdateAttendanceController::class)
    ->middleware(['auth:api']);

