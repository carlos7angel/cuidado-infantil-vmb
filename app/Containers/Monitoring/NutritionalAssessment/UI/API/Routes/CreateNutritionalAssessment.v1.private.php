<?php

/**
 * @apiGroup           NutritionalAssessment
 * @apiName            Create
 *
 * @api                {POST} /v1/children/{child_id}/nutritional-assessments Invoke
 * @apiDescription     Crear una nueva valoración nutricional para un niño. Calcula automáticamente todos los z-scores según estándares WHO.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated ['permissions' => '', 'roles' => '']
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} child_id ID del niño (en la URL)
 * @apiParam           {Number} weight Peso en kg (requerido)
 * @apiParam           {Number} height Talla/Longitud en cm (requerido)
 * @apiParam           {Number} [head_circumference] Perímetro cefálico en cm (opcional)
 * @apiParam           {Number} [arm_circumference] Perímetro braquial en cm (opcional)
 * @apiParam           {String} [assessment_date] Fecha de la valoración (formato: Y-m-d). Por defecto: fecha actual
 * @apiParam           {String} [observations] Observaciones (opcional)
 * @apiParam           {String} [recommendations] Recomendaciones (opcional)
 * @apiParam           {String} [actions_taken] Acciones tomadas en referencia a la evaluación anterior (opcional)
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 201 Created
 * {
 *     "data": {
 *         "id": "xxx",
 *         "child_id": "xxx",
 *         "assessment_date": "2025-12-26",
 *         "age_in_months": 18,
 *         "weight": 11.5,
 *         "height": 82.0,
 *         "z_weight_age": 0.25,
 *         "z_height_age": -0.15,
 *         "z_weight_height": 0.30,
 *         "z_bmi_age": 0.20,
 *         "status_weight_age": "normal",
 *         "status_height_age": "normal",
 *         "status_weight_height": "normal",
 *         "status_bmi_age": "normal",
 *         "observations": "Niño activo y con buen apetito",
 *         "recommendations": "Reforzar consumo de frutas y verduras",
 *         "actions_taken": "Se ajustó el plan alimentario aumentando la frecuencia de meriendas",
 *         "bmi": 17.08,
 *         "created_at": "2025-12-26T10:00:00.000000Z"
 *     }
 * }
 */

use App\Containers\Monitoring\NutritionalAssessment\UI\API\Controllers\CreateNutritionalAssessmentController;
use Illuminate\Support\Facades\Route;

Route::post('children/{child_id}/nutritional-assessments', CreateNutritionalAssessmentController::class)
    ->middleware(['auth:api']);
