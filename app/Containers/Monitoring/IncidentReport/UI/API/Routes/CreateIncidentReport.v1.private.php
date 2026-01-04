<?php

/**
 * @apiGroup           IncidentReport
 * @apiName            CreateIncidentReport
 *
 * @api                {POST} /v1/incident-reports Crear reporte de incidente
 * @apiDescription     Crea un nuevo reporte de incidente o maltrato infantil.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated ['permissions' => '', 'roles' => '']
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} child_id (obligatorio) ID del niño involucrado
 * @apiParam           {String} type (obligatorio) Tipo de incidente: accidente, conducta_inapropiada, lesion_fisica, negligencia, maltrato_psicologico, maltrato_fisico, otro
 * @apiParam           {String} severity_level (obligatorio) Nivel de gravedad: leve, moderado, grave, critico
 * @apiParam           {String} description (obligatorio) Descripción detallada del incidente (mínimo 10 caracteres)
 * @apiParam           {Date} incident_date (obligatorio) Fecha del incidente (formato: YYYY-MM-DD)
 * @apiParam           {Time} incident_time (opcional) Hora del incidente (formato: HH:mm)
 * @apiParam           {String} incident_location (obligatorio) Lugar donde ocurrió el incidente
 * @apiParam           {String} people_involved (obligatorio) Personas involucradas en el incidente
 * @apiParam           {String} witnesses (opcional) Testigos del incidente
 * @apiParam           {Boolean} has_evidence (opcional) Indica si existe evidencia
 * @apiParam           {String} evidence_description (opcional) Descripción de la evidencia
 * @apiParam           {File[]} evidence_files (opcional) Archivos de evidencia (fotografías: jpeg, jpg, png, gif, webp, máximo 10MB cada uno)
 * @apiParam           {String} actions_taken (opcional) Acciones tomadas
 * @apiParam           {String} additional_comments (opcional) Comentarios adicionales
 * @apiParam           {String} childcare_center_id (opcional) ID del centro de cuidado (se obtiene automáticamente del enrollment activo si no se proporciona)
 * @apiParam           {String} room_id (opcional) ID de la sala (se obtiene automáticamente del enrollment activo si no se proporciona)
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 201 Created
 * {
 *     "data": {
 *         "type": "incident_report",
 *         "id": "abc123",
 *         "code": "INC-20251228-0001",
 *         "status": "reportado",
 *         "status_label": "Reportado",
 *         "type": "accidente",
 *         "type_label": "Accidente",
 *         "severity_level": "leve",
 *         "severity_label": "Leve",
 *         "severity_color": "#4CAF50",
 *         "description": "Descripción del incidente...",
 *         "incident_date": "2025-12-28",
 *         "incident_date_readable": "28/12/2025",
 *         "incident_time": "14:30",
 *         "incident_location": "Sala de juegos",
 *         "people_involved": "Niño y educador",
 *         "witnesses": null,
 *         "has_evidence": false,
 *         "requires_immediate_attention": false,
 *         "is_active": true,
 *         "created_at": "2025-12-28 10:30:00"
 *     }
 * }
 */

use App\Containers\Monitoring\IncidentReport\UI\API\Controllers\CreateIncidentReportController;
use Illuminate\Support\Facades\Route;

Route::post('incident-reports', CreateIncidentReportController::class)
    ->middleware(['auth:api']);

