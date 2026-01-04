<?php

/**
 * @apiGroup           Attendance
 * @apiName            UpsertAttendance
 *
 * @api                {PUT} /v1/attendances/upsert Registrar/Actualizar asistencia
 * @apiDescription     Registra o actualiza la asistencia de un niño en una fecha específica.
 *                     Si ya existe un registro con el mismo niño, centro y fecha, solo actualiza el status.
 *                     Si no existe, crea un nuevo registro.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated ['permissions' => '', 'roles' => '']
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} child_id ID del niño (hashed, requerido)
 * @apiParam           {String} childcare_center_id ID del centro de cuidado infantil (hashed, requerido)
 * @apiParam           {String} date Fecha de asistencia (Y-m-d, requerido)
 * @apiParam           {String} status Estado de asistencia (presente, ausente, tardanza, etc., requerido)
 * @apiParam           {String} [check_in_time] Hora de entrada (H:i, opcional)
 * @apiParam           {String} [check_out_time] Hora de salida (H:i, opcional)
 * @apiParam           {String} [observations] Observaciones (opcional, max 1000 caracteres)
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     "data": {
 *         "type": "attendance",
 *         "id": "xxx",
 *         "child_id": "yyy",
 *         "childcare_center_id": "zzz",
 *         "date": "2025-01-15",
 *         "status": "presente",
 *         "check_in_time": "08:15",
 *         "check_out_time": null,
 *         "observations": null
 *     }
 * }
 */

use App\Containers\Monitoring\Attendance\UI\API\Controllers\UpsertAttendanceController;
use Illuminate\Support\Facades\Route;

Route::put('attendance/upsert', UpsertAttendanceController::class)
    ->middleware(['auth:api']);

