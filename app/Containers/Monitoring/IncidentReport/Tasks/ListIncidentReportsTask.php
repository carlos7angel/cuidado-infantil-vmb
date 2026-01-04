<?php

namespace App\Containers\Monitoring\IncidentReport\Tasks;

use App\Containers\Monitoring\IncidentReport\Data\Repositories\IncidentReportRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class ListIncidentReportsTask extends ParentTask
{
    public function __construct(
        private readonly IncidentReportRepository $repository,
    ) {
    }

    public function run(?int $childcareCenterId = null): mixed
    {
        // Usar repositorio para mantener paginación implícita automática
        // addRequestCriteria() maneja automáticamente los parámetros de paginación del request
        // (limit, page, etc.) sin necesidad de pasarlos manualmente
        return $this->repository
            ->scopeQuery(function ($query) use ($childcareCenterId) {
                // Filtrar por childcare_center_id si se proporciona
                if ($childcareCenterId !== null) {
                    $query->where('childcare_center_id', $childcareCenterId);
                }
                
                // Ordenar por fecha de reporte (reported_at) o fecha de creación (created_at)
                // Prioridad: reported_at si existe, sino created_at
                // Orden descendente: más recientes primero
                $query->orderByDesc('reported_at')
                    ->orderByDesc('created_at');
                
                return $query;
            })
            ->addRequestCriteria()
            ->with(['child', 'room']) // Cargar relaciones child y room para el transformer
            ->paginate(); // Paginación automática basada en parámetros del request
    }
}
