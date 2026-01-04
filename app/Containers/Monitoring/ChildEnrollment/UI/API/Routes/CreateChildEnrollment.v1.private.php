<?php

/**
 * @apiGroup           ChildEnrollment
 * @apiName            Create
 *
 * @api                {POST} /v1/child-enrollments Invoke
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

use App\Containers\Monitoring\ChildEnrollment\UI\API\Controllers\CreateChildEnrollmentController;
use Illuminate\Support\Facades\Route;

Route::post('child-enrollments', CreateChildEnrollmentController::class)
    ->middleware(['auth:api']);

