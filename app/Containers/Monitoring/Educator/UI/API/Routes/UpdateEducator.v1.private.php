<?php

/**
 * @apiGroup           Educator
 * @apiName            UpdateEducator
 *
 * @api                {PATCH} /v1/educators/:id Actualizar educador
 * @apiDescription     Actualiza los datos de un educador. El email no puede ser modificado.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated ['permissions' => '', 'roles' => '']
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} id (obligatorio) ID del educador
 * @apiParam           {String} [first_name] Nombre del educador
 * @apiParam           {String} [last_name] Apellido del educador
 * @apiParam           {String} [gender] Género: masculino, femenino, no_especificado
 * @apiParam           {Date} [birth] Fecha de nacimiento (formato: YYYY-MM-DD)
 * @apiParam           {String} [state] Departamento/estado
 * @apiParam           {String} [dni] DNI del educador
 * @apiParam           {String} [phone] Teléfono del educador
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     "data": {
 *         "type": "educator",
 *         "id": "abc123",
 *         "first_name": "Juan",
 *         "last_name": "Pérez García",
 *         "full_name": "Juan Pérez García",
 *         "gender": "masculino",
 *         "birth": "1990-05-15",
 *         "birth_readable": "15/05/1990",
 *         "state": "La Paz",
 *         "dni": "12345678",
 *         "phone": "+591 71234567",
 *         "created_at": "2025-12-28 10:30:00",
 *         "updated_at": "2025-12-28 11:00:00",
 *         "user": {
 *             "type": "user",
 *             "id": "xyz789",
 *             "name": "Juan Pérez",
 *             "email": "juan.perez@example.com"
 *         }
 *     }
 * }
 */

use App\Containers\Monitoring\Educator\UI\API\Controllers\UpdateEducatorController;
use Illuminate\Support\Facades\Route;

Route::put('educators/{id}', UpdateEducatorController::class)
    ->middleware(['auth:api']);

