<?php

/**
 * @apiGroup           ChildDevelopment
 * @apiName            FindEvaluationById
 *
 * @api                {GET} /v1/development-evaluations/{id} Obtener evaluación por ID
 * @apiDescription     Obtiene el detalle completo de una evaluación de desarrollo infantil. Incluye información del niño, todos los ítems evaluados con sus resultados, puntajes calculados por área, análisis de desarrollo y recomendaciones.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated ['permissions' => '', 'roles' => '']
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer {token}
 *
 * @apiParam           {String} id ID hasheado de la evaluación de desarrollo
 * @apiParam           {String} [include] Relaciones a incluir: items, scores, child, assessed_by (separadas por coma)
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     "data": {
 *         "type": "child_development_evaluation",
 *         "id": "xxx",
 *         "child_id": "xxx",
 *         "evaluation_date": "2025-12-26",
 *         "age_months": 24,
 *         "age_readable": "2 años",
 *         "scores": {
 *             "MG": {
 *                 "raw_score": 18,
 *                 "max_possible_score": 22,
 *                 "percentage": 81.82,
 *                 "status": "medio_alto",
 *                 "status_label": "Medio Alto"
 *             }
 *         },
 *         "requires_attention": false,
 *         "items": [...]
 *     }
 * }
 */

use App\Containers\Monitoring\ChildDevelopment\UI\API\Controllers\FindChildDevelopmentEvaluationByIdController;
use Illuminate\Support\Facades\Route;

Route::get('development-evaluations/{id}', FindChildDevelopmentEvaluationByIdController::class)
    ->middleware(['auth:api']);

