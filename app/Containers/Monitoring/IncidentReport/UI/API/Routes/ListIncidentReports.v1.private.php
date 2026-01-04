<?php

/**
 * @apiGroup           IncidentReport
 * @apiName            ListIncidentReports
 *
 * @api                {GET} /v1/childcare-centers/{childcare_center_id}/incident-reports Listar reportes de incidentes
 * @apiDescription     Lista los reportes de incidentes de un centro de cuidado infantil. Incluye paginación implícita.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated ['permissions' => '', 'roles' => '']
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} childcare_center_id (obligatorio) ID del centro de cuidado infantil
 * @apiParam           {Number} [page=1] Número de página (paginación implícita)
 * @apiParam           {Number} [limit=15] Cantidad de resultados por página (paginación implícita)
 * @apiParam           {String} [search] Búsqueda por código, descripción, etc.
 * @apiParam           {String} [status] Filtrar por estado: reportado, en_revision, cerrado, escalado, archivado
 * @apiParam           {String} [type] Filtrar por tipo: accidente, conducta_inapropiada, lesion_fisica, negligencia, maltrato_psicologico, maltrato_fisico, otro
 * @apiParam           {String} [severity_level] Filtrar por gravedad: leve, moderado, grave, critico
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     "data": [
 *         {
 *             "type": "incident_report",
 *             "id": "abc123",
 *             "code": "INC-20251228-0001",
 *             "status": "reportado",
 *             "status_label": "Reportado",
 *             "type": "accidente",
 *             "type_label": "Accidente",
 *             "severity_level": "leve",
 *             "severity_label": "Leve",
 *             "severity_color": "#4CAF50",
 *             "description": "El niño se cayó...",
 *             "incident_date": "2025-12-28",
 *             "incident_date_readable": "28/12/2025",
 *             "incident_location": "Patio del centro",
 *             "child_id": "xyz789",
 *             "child_name": "Juan Pérez García",
 *             "has_evidence": true,
 *             "evidence_files_count": 2,
 *             "requires_immediate_attention": false,
 *             "is_active": true,
 *             "reported_at": "2025-12-28 10:30:00",
 *             "created_at": "2025-12-28 10:30:00"
 *         }
 *     ],
 *     "meta": {
 *         "pagination": {
 *             "total": 50,
 *             "count": 15,
 *             "per_page": 15,
 *             "current_page": 1,
 *             "total_pages": 4
 *         }
 *     }
 * }
 */

use App\Containers\Monitoring\IncidentReport\UI\API\Controllers\ListIncidentReportsController;
use Illuminate\Support\Facades\Route;

Route::get('childcare-centers/{childcare_center_id}/incident-reports', ListIncidentReportsController::class)
    ->middleware(['auth:api']);

