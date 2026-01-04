<?php

/**
 * @apiGroup           Attendance
 * @apiName            ListChildrenAttendanceByDateRange
 *
 * @api                {GET} /v1/attendances/by-childcare-center Obtener asistencias por rango de fechas
 * @apiDescription     Obtiene las asistencias de todos los niños inscritos activos de un centro de cuidado infantil
 *                     en un rango de fechas específico. Si no se proporcionan fechas, usa por defecto los últimos 6 días,
 *                     el día actual y mañana (8 días en total).
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated ['permissions' => '', 'roles' => '']
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} childcare_center_id ID del centro de cuidado infantil (hashed, requerido)
 * @apiParam           {String} [start_date] Fecha de inicio (Y-m-d). Default: 6 días atrás
 * @apiParam           {String} [end_date] Fecha de fin (Y-m-d). Default: mañana
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     "data": {
 *         "center_id": 10,
 *         "range": {
 *             "start": "2025-01-01",
 *             "end": "2025-01-10"
 *         },
 *         "children_count": 3,
 *         "dates": ["2025-01-01", "2025-01-02", ...],
 *         "children": [
 *             {
 *                 "child_id": 120,
 *                 "full_name": "María Soto",
 *                 "group_name": "Sala 3 años",
 *                 "attendance": {
 *                     "2025-01-01": "present",
 *                     "2025-01-02": "not_recorded",
 *                     "2025-01-03": null
 *                 }
 *             }
 *         ]
 *     }
 * }
 */

use App\Containers\Monitoring\Attendance\UI\API\Controllers\ListChildrenAttendanceByDateRangeController;
use Illuminate\Support\Facades\Route;

Route::get('attendance/childcare-center/{childcare_center_id}', ListChildrenAttendanceByDateRangeController::class)
    ->middleware(['auth:api']);

