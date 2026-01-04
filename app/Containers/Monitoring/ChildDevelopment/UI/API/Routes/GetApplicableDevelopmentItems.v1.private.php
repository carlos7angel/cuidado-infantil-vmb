<?php

/**
 * @apiGroup           ChildDevelopment
 * @apiName            GetApplicableDevelopmentItems
 *
 * @api                {GET} /v1/children/{child_id}/development-items Obtener ítems acumulados
 * @apiDescription     Obtiene TODOS los ítems de desarrollo acumulados hasta la edad del niño. Esto incluye todos los ítems que el niño debería haber alcanzado desde su nacimiento hasta su edad actual. Los ítems están agrupados por área (MG, MF, AL, PS). La edad se calcula automáticamente desde la fecha de nacimiento del niño y la fecha de evaluación proporcionada (o fecha actual si no se proporciona). Cada evaluación es independiente y evalúa todos los hitos acumulados hasta esa edad.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated ['permissions' => '', 'roles' => '']
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer {token}
 *
 * @apiParam           {String} child_id ID hasheado del niño (en la URL)
 * @apiParam           {String} [evaluation_date] Fecha de evaluación para calcular la edad (formato: Y-m-d). Por defecto: fecha actual
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     "data": {
 *         "child": {
 *             "id": "xxx",
 *             "full_name": "Juan Pérez García",
 *             "birth_date": "2023-12-26"
 *         },
 *         "evaluation_info": {
 *             "age_months": 24,
 *             "age_readable": "2 años"
 *         },
 *         "items_by_area": {
 *             "MG": {
 *                 "area": "MG",
 *                 "area_label": "Motricidad Gruesa",
 *                 "items": [
 *                     {
 *                         "id": "xxx",
 *                         "item_number": 16,
 *                         "description": "Patea la pelota",
 *                         "age_min_months": 19,
 *                         "age_max_months": 24
 *                     }
 *                 ],
 *                 "total_items": 3
 *             },
 *             "MF": { ... },
 *             "AL": { ... },
 *             "PS": { ... }
 *         },
 *         "total_items": 12
 *     }
 * }
 */

use App\Containers\Monitoring\ChildDevelopment\UI\API\Controllers\GetApplicableDevelopmentItemsController;
use Illuminate\Support\Facades\Route;

Route::get('children/{child_id}/development-items', GetApplicableDevelopmentItemsController::class)
    ->middleware(['auth:api']);

