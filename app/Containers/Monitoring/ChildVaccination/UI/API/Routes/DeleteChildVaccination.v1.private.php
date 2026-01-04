<?php

/**
 * @apiGroup           ChildVaccination
 * @apiName            DeleteChildVaccination
 *
 * @api                {DELETE} /v1/child-vaccinations/{id} Delete Child Vaccination
 * @apiDescription     Delete a child vaccination record by ID. This will permanently remove the vaccination record from the database.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated ['permissions' => '', 'roles' => '']
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} id Child Vaccination ID (hashed) - from route parameter
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 204 No Content
 *
 * @apiErrorExample    {json} Error-Response (Not Found):
 * HTTP/1.1 404 Not Found
 * {
 *     "message": "No query results for model [App\\Containers\\Monitoring\\ChildVaccination\\Models\\ChildVaccination] {id}"
 * }
 *
 * @apiErrorExample    {json} Error-Response (Validation):
 * HTTP/1.1 422 Unprocessable Entity
 * {
 *     "message": "The given data was invalid.",
 *     "errors": {
 *         "id": [
 *             "El registro de vacunación especificado no existe."
 *         ]
 *     }
 * }
 */

use App\Containers\Monitoring\ChildVaccination\UI\API\Controllers\DeleteChildVaccinationController;
use Illuminate\Support\Facades\Route;

Route::post('child-vaccinations/{id}/delete', DeleteChildVaccinationController::class)
    ->middleware(['auth:api']);

