<?php

/**
 * @apiGroup           Attendance
 * @apiName            Create
 *
 * @api                {POST} /v1/attendances Invoke
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

use App\Containers\Monitoring\Attendance\UI\API\Controllers\CreateAttendanceController;
use Illuminate\Support\Facades\Route;

Route::post('attendances', CreateAttendanceController::class)
    ->middleware(['auth:api']);

