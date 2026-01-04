<?php

/**
 * @apiGroup           ChildDevelopment
 * @apiName            ListEvaluationsByChild
 *
 * @api                {GET} /v1/children/{child_id}/development-evaluations Listar evaluaciones por niño
 * @apiDescription     Lista todas las evaluaciones de desarrollo de un niño específico, ordenadas desde la más reciente. Respuesta optimizada con solo información esencial ya guardada, sin procesamiento pesado.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated ['permissions' => '', 'roles' => '']
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer {token}
 *
 * @apiParam           {String} child_id ID hasheado del niño
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     "data": [
 *         {
 *             "type": "child_development_evaluation",
 *             "id": "xxx",
 *             "evaluation_date": "2025-12-26",
 *             "evaluation_date_readable": "26/12/2025",
 *             "age_months": 24,
 *             "age_readable": "2 años",
 *             "scores": {
 *                 "MG": {
 *                     "raw_score": 18,
 *                     "status": "medio_alto",
 *                     "status_label": "Medio Alto",
 *                     "status_color": "#4CAF50"
 *                 },
 *                 "MF": { ... },
 *                 "AL": { ... },
 *                 "PS": { ... }
 *             },
 *             "requires_attention": false,
 *             "next_evaluation_date": "2026-03-26",
 *             "created_at": "2025-12-26 10:30:00"
 *         }
 *     ]
 * }
 */

use App\Containers\Monitoring\ChildDevelopment\UI\API\Controllers\ListChildDevelopmentEvaluationsController;
use Illuminate\Support\Facades\Route;

Route::get('children/{child_id}/development-evaluations', ListChildDevelopmentEvaluationsController::class)
    ->middleware(['auth:api']);

