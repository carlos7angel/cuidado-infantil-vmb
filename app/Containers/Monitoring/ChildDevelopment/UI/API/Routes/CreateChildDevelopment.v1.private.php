<?php

/**
 * @apiGroup           ChildDevelopment
 * @apiName            Create
 *
 * @api                {POST} /v1/children/{child_id}/development-evaluations Crear evaluación de desarrollo
 * @apiDescription     Crear una nueva evaluación de desarrollo infantil para un niño. Calcula automáticamente los puntajes por área según las normas de desarrollo.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated ['permissions' => '', 'roles' => '']
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer {token}
 *
 * @apiParam           {String} child_id ID hasheado del niño (en la URL)
 * @apiParam           {String} [evaluation_date] Fecha de evaluación (formato: Y-m-d). Por defecto: fecha actual
 * @apiParam           {Number} [age_months] Edad en meses. Si no se proporciona, se calcula desde la fecha de nacimiento
 * @apiParam           {Number} [weight] Peso en kg (opcional)
 * @apiParam           {Number} [height] Altura en cm (opcional)
 * @apiParam           {String} [notes] Observaciones (opcional)
 * @apiParam           {String} [next_evaluation_date] Próxima fecha de evaluación recomendada (opcional)
 * @apiParam           {Array} items Array de IDs de ítems logrados (requerido). Solo se envían los items que fueron logrados (achieved=true). El backend reconstruirá el historial completo al mostrar la evaluación.
 * @apiParam           {Number} items[] ID del ítem de desarrollo que fue logrado
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 201 Created
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
 *                 "status": "medio_alto",
 *                 "status_label": "Medio Alto"
 *             }
 *         },
 *         "requires_attention": false
 *     }
 * }
 */

use App\Containers\Monitoring\ChildDevelopment\UI\API\Controllers\CreateChildDevelopmentEvaluationController;
use Illuminate\Support\Facades\Route;

Route::post('children/{child_id}/development-evaluations', CreateChildDevelopmentEvaluationController::class)
    ->middleware(['auth:api']);

