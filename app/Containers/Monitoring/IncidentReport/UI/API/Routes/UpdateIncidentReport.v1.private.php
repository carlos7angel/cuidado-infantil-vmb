<?php

/**
 * @apiGroup           IncidentReport
 * @apiName            UpdateIncidentReport
 *
 * @api                {PATCH} /v1/incident-reports/:id Actualizar reporte de incidente
 * @apiDescription     Actualiza campos específicos de un reporte de incidente existente.
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated ['permissions' => '', 'roles' => '']
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} id (obligatorio) ID del reporte de incidente
 * @apiParam           {String} [description] Descripción detallada del incidente (mínimo 10 caracteres, máximo 5000)
 * @apiParam           {String} [actions_taken] Acciones tomadas (máximo 2000 caracteres)
 * @apiParam           {String} [additional_comments] Comentarios adicionales (máximo 2000 caracteres)
 * @apiParam           {String} [follow_up_notes] Notas de seguimiento (máximo 2000 caracteres)
 * @apiParam           {String} [authority_notification_details] Detalles de notificación a autoridades (máximo 2000 caracteres)
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
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
 *         "description": "Descripción actualizada del incidente...",
 *         "incident_date": "2025-12-28",
 *         "incident_date_readable": "28/12/2025",
 *         "incident_time": "14:30",
 *         "incident_location": "Sala de juegos",
 *         "people_involved": "Niño y educador",
 *         "witnesses": null,
 *         "has_evidence": false,
 *         "actions_taken": "Se aplicó hielo en la zona afectada",
 *         "additional_comments": "El niño se encuentra bien",
 *         "follow_up_notes": "Monitorear durante 24 horas",
 *         "authority_notification_details": "No fue necesario notificar a autoridades",
 *         "escalated_to": "Director del Centro",
 *         "requires_immediate_attention": false,
 *         "is_active": true,
 *         "created_at": "2025-12-28 10:30:00",
 *         "updated_at": "2025-12-28 11:00:00"
 *     }
 * }
 */

use App\Containers\Monitoring\IncidentReport\UI\API\Controllers\UpdateIncidentReportController;
use Illuminate\Support\Facades\Route;

Route::put('incident-reports/{id}', UpdateIncidentReportController::class)
    ->middleware(['auth:api']);

