<?php

/**
 * @apiGroup           ChildVaccination
 * @apiName            FindById
 *
 * @api                {GET} /v1/no/:id Invoke
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

use App\Containers\Monitoring\ChildVaccination\UI\API\Controllers\FindChildVaccinationByIdController;
use Illuminate\Support\Facades\Route;

Route::get('no/{id}', FindChildVaccinationByIdController::class)
    ->middleware(['auth:api']);

