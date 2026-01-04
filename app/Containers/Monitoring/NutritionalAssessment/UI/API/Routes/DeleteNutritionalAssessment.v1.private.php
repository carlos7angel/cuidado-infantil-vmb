<?php

/**
 * @apiGroup           NutritionalAssessment
 * @apiName            Delete
 *
 * @api                {DELETE} /v1/nutritional-assessments/:id Invoke
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

use App\Containers\Monitoring\NutritionalAssessment\UI\API\Controllers\DeleteNutritionalAssessmentController;
use Illuminate\Support\Facades\Route;

Route::delete('nutritional-assessments/{id}', DeleteNutritionalAssessmentController::class)
    ->middleware(['auth:api']);

