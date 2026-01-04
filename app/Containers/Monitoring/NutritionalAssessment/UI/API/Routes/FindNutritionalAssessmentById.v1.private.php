<?php

/**
 * @apiGroup           NutritionalAssessment
 * @apiName            FindNutritionalAssessmentById
 *
 * @api                {GET} /v1/nutritional-assessments/:id Obtener evaluación nutricional por ID
 * @apiDescription     Obtiene una evaluación nutricional específica por su ID. Retorna todos los datos de la evaluación incluyendo z-scores, clasificaciones nutricionales e interpretaciones.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated ['permissions' => '', 'roles' => '']
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer {token}
 *
 * @apiParam           {String} id ID hasheado de la evaluación nutricional
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     "data": {
 *         "type": "NutritionalAssessment",
 *         "id": "9RQw2L01WAGmo3yr",
 *         "child_id": "4",
 *         "assessment_date": "2025-12-26",
 *         "age_in_months": 21,
 *         "age_readable": "1a 9m",
 *         "weight": "20.50",
 *         "height": "82.00",
 *         "head_circumference": null,
 *         "arm_circumference": null,
 *         "bmi": 15.25,
 *         "z_weight_age": "0.50",
 *         "z_height_age": "-0.30",
 *         "z_weight_height": "0.20",
 *         "z_bmi_age": "0.10",
 *         "status_weight_age": "normal",
 *         "status_weight_age_label": "Normal",
 *         "status_weight_age_interpretation": "Peso adecuado para la edad",
 *         "status_height_age": "normal",
 *         "status_height_age_label": "Normal",
 *         "status_height_age_interpretation": "Crecimiento lineal adecuado",
 *         "status_weight_height": "normal",
 *         "status_weight_height_label": "Normal",
 *         "status_weight_height_interpretation": "Peso proporcional a la estatura",
 *         "status_bmi_age": "normal",
 *         "status_bmi_age_label": "Normal",
 *         "status_bmi_age_interpretation": "IMC adecuado para la edad",
 *         "requires_attention": false,
 *         "critical_status": null,
 *         "critical_status_label": null,
 *         "observations": null,
 *         "recommendations": null,
 *         "next_assessment_date": null,
 *         "created_at": "2025-12-26T03:48:34.000000Z",
 *         "updated_at": "2025-12-26T03:48:34.000000Z",
 *         "readable_created_at": "hace 2 horas",
 *         "readable_updated_at": "hace 2 horas"
 *     },
 *     "meta": {
 *         "include": []
 *     }
 * }
 */

use App\Containers\Monitoring\NutritionalAssessment\UI\API\Controllers\FindNutritionalAssessmentByIdController;
use Illuminate\Support\Facades\Route;

Route::get('nutritional-assessments/{id}', FindNutritionalAssessmentByIdController::class)
    ->middleware(['auth:api']);

