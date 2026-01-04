<?php

/**
 * @apiGroup           ChildVaccination
 * @apiName            GetChildVaccinationTracking
 *
 * @api                {GET} /v1/children/{child_id}/vaccination-tracking Get Child Vaccination Tracking
 * @apiDescription     Get complete vaccination tracking and status for a child. Returns optimized structure with vaccines, doses, status, and timeline.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated ['permissions' => '', 'roles' => '']
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} child_id Child ID (hashed)
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     "data": {
 *         "child": {
 *             "id": "hashed_id",
 *             "name": "Nombre Completo",
 *             "age_in_months": 18,
 *             "age_readable": "1 año y 6 meses",
 *             "birth_date": "2023-06-15"
 *         },
 *         "summary": {
 *             "total_vaccines": 12,
 *             "total_doses": 25,
 *             "applied_count": 8,
 *             "pending_count": 17,
 *             "overdue_count": 1,
 *             "upcoming_count": 2,
 *             "expired_count": 0,
 *             "completion_percentage": 32.0
 *         },
 *         "vaccines": [...],
 *         "timeline": [...]
 *     }
 * }
 */

use App\Containers\Monitoring\ChildVaccination\UI\API\Controllers\GetChildVaccinationTrackingController;
use Illuminate\Support\Facades\Route;

Route::get('children/{child_id}/vaccination-tracking', GetChildVaccinationTrackingController::class)
    ->middleware(['auth:api']);

