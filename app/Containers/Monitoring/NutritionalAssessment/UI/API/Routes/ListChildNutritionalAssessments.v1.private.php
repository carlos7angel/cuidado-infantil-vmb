<?php

/**
 * @apiGroup           NutritionalAssessment
 * @apiName            ListChildNutritionalAssessments
 *
 * @api                {GET} /v1/children/{child_id}/nutritional-assessments Listar evaluaciones nutricionales de un niño
 * @apiDescription     Obtiene todas las evaluaciones nutricionales de un niño específico, ordenadas del más reciente al más antiguo por fecha de evaluación.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated ['permissions' => '', 'roles' => '']
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer {token}
 *
 * @apiParam           {String} child_id ID hasheado del niño en la URL
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     "data": [
 *         {
 *             "type": "NutritionalAssessment",
 *             "id": "9RQw2L01WAGmo3yr",
 *             "child_id": "4",
 *             "assessment_date": "2025-12-26",
 *             "age_in_months": 21,
 *             "age_readable": "1a 9m",
 *             "weight": "20.50",
 *             "height": "82.00",
 *             "head_circumference": null,
 *             "arm_circumference": null,
 *             "bmi": 15.25,
 *             "z_weight_age": "0.50",
 *             "z_height_age": "-0.30",
 *             "z_weight_height": "0.20",
 *             "z_bmi_age": "0.10",
 *             "status_weight_age": "normal",
 *             "status_weight_age_label": "Normal",
 *             "status_weight_age_interpretation": "Peso adecuado para la edad",
 *             "status_height_age": "normal",
 *             "status_height_age_label": "Normal",
 *             "status_height_age_interpretation": "Crecimiento lineal adecuado",
 *             "status_weight_height": "normal",
 *             "status_weight_height_label": "Normal",
 *             "status_weight_height_interpretation": "Peso proporcional a la estatura",
 *             "status_bmi_age": "normal",
 *             "status_bmi_age_label": "Normal",
 *             "status_bmi_age_interpretation": "IMC adecuado para la edad",
 *             "requires_attention": false,
 *             "critical_status": null,
 *             "critical_status_label": null,
 *             "observations": null,
 *             "recommendations": null,
 *             "next_assessment_date": null,
 *             "created_at": "2025-12-26T03:48:34.000000Z",
 *             "updated_at": "2025-12-26T03:48:34.000000Z",
 *             "readable_created_at": "hace 2 horas",
 *             "readable_updated_at": "hace 2 horas"
 *         }
 *     ],
 *     "meta": {
 *         "pagination": {
 *             "total": 10,
 *             "count": 10,
 *             "per_page": 15,
 *             "current_page": 1,
 *             "total_pages": 1
 *         }
 *     }
 * }
 */

use App\Containers\Monitoring\NutritionalAssessment\UI\API\Controllers\ListChildNutritionalAssessmentsController;
use Illuminate\Support\Facades\Route;

Route::get('children/{child_id}/nutritional-assessments', ListChildNutritionalAssessmentsController::class)
    ->middleware(['auth:api']);

