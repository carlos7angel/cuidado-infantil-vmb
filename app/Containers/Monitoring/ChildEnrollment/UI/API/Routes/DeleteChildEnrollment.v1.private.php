<?php

/**
 * @apiGroup           ChildEnrollment
 * @apiName            Delete
 *
 * @api                {DELETE} /v1/child-enrollments/:id Invoke
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

use App\Containers\Monitoring\ChildEnrollment\UI\API\Controllers\DeleteChildEnrollmentController;
use Illuminate\Support\Facades\Route;

Route::delete('child-enrollments/{id}', DeleteChildEnrollmentController::class)
    ->middleware(['auth:api']);

