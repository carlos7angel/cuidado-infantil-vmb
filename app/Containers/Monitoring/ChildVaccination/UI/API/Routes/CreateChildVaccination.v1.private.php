<?php

/**
 * @apiGroup           ChildVaccination
 * @apiName            CreateChildVaccination
 *
 * @api                {POST} /v1/children/{child_id}/vaccinations Register Child Vaccination
 * @apiDescription     Register a vaccine dose application for a child. Validates that the child doesn't already have this dose and that the child's age is within the valid range for the dose.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated ['permissions' => '', 'roles' => '']
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} child_id Child ID (hashed) - from route parameter
 * @apiParam           {String} vaccine_dose_id Vaccine Dose ID (hashed) - required
 * @apiParam           {Date} date_applied Date when the vaccine was applied (Y-m-d format) - required, cannot be future date
 * @apiParam           {String} [applied_at] Place where the vaccine was applied - optional, max 255 characters
 * @apiParam           {String} [notes] Additional notes about the vaccination - optional, max 1000 characters
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 201 Created
 * {
 *     "data": {
 *         "type": "ChildVaccination",
 *         "id": "hashed_id",
 *         "child": {
 *             "id": "hashed_id",
 *             "name": "Nombre Completo"
 *         },
 *         "vaccine": {
 *             "id": "hashed_id",
 *             "name": "BCG",
 *             "description": "Vacuna contra la tuberculosis"
 *         },
 *         "dose": {
 *             "id": "hashed_id",
 *             "dose_number": 1,
 *             "recommended_age_months": 0,
 *             "recommended_age_readable": "al nacer",
 *             "age_range_readable": "al nacer - sin límite"
 *         },
 *         "date_applied": "2023-06-20",
 *         "applied_at": "Centro de Salud Principal",
 *         "notes": "Sin reacciones adversas",
 *         "age_at_application_months": 0,
 *         "age_at_application_readable": "al nacer",
 *         "created_at": "2023-06-20T10:00:00.000000Z",
 *         "updated_at": "2023-06-20T10:00:00.000000Z"
 *     }
 * }
 *
 * @apiErrorExample    {json} Error-Response (Validation):
 * HTTP/1.1 422 Unprocessable Entity
 * {
 *     "message": "The given data was invalid.",
 *     "errors": {
 *         "vaccine_dose_id": [
 *             "El niño ya tiene registrada la dosis 1 de la vacuna BCG."
 *         ]
 *     }
 * }
 *
 * @apiErrorExample    {json} Error-Response (Age Validation):
 * HTTP/1.1 422 Unprocessable Entity
 * {
 *     "message": "The given data was invalid.",
 *     "errors": {
 *         "date_applied": [
 *             "El niño tiene 1 mes al momento de la aplicación, pero la edad mínima para esta dosis es 2 meses."
 *         ]
 *     }
 * }
 */

use App\Containers\Monitoring\ChildVaccination\UI\API\Controllers\CreateChildVaccinationController;
use Illuminate\Support\Facades\Route;

Route::post('children/{child_id}/vaccinations', CreateChildVaccinationController::class)
    ->middleware(['auth:api']);

