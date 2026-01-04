<?php

/**
 * @apiGroup           ChildcareCenter
 * @apiName            ListActiveChildren
 *
 * @api                {GET} /v1/childcare-centers/:id/active-children Obtener niños activos
 * @apiDescription     Obtiene todos los niños inscritos activos de un centro de cuidado infantil específico. Solo devuelve los datos esenciales de identificación.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated ['permissions' => '', 'roles' => '']
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} id ID del centro de cuidado infantil (hashed)
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     "data": [
 *         {
 *             "type": "child",
 *             "id": "xxx",
 *             "identification": {
 *                 "first_name": "Juan",
 *                 "paternal_last_name": "Pérez",
 *                 "maternal_last_name": "García",
 *                 "full_name": "Juan Pérez García",
 *                 "birth_date": "2020-05-15",
 *                 "age": 4,
 *                 "gender": "male"
 *             }
 *         }
 *     ]
 * }
 */

use App\Containers\Monitoring\ChildcareCenter\UI\API\Controllers\ListChildrenByChildcareCenterController;
use Illuminate\Support\Facades\Route;

Route::get('childcare-centers/{id}/children', ListChildrenByChildcareCenterController::class)
    ->middleware(['auth:api']);

