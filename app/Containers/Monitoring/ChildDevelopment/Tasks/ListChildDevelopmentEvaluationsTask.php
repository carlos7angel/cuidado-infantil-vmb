<?php

namespace App\Containers\Monitoring\ChildDevelopment\Tasks;

use App\Containers\Monitoring\ChildDevelopment\Data\Repositories\ChildDevelopmentEvaluationRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class ListChildDevelopmentEvaluationsTask extends ParentTask
{
    public function __construct(
        private readonly ChildDevelopmentEvaluationRepository $repository,
    ) {
    }

    public function run(int $childId): mixed
    {
        // Usar repositorio para mantener paginación implícita automática
        // addRequestCriteria() maneja automáticamente los parámetros de paginación del request
        // (limit, page, etc.) sin necesidad de pasarlos manualmente
        return $this->repository
            ->scopeQuery(function ($query) use ($childId) {
                return $query->where('child_id', $childId)
                    ->orderByDesc('evaluation_date');
            })
            ->addRequestCriteria()
            ->with('scores') // Solo scores, sin items ni otras relaciones pesadas
            ->paginate(); // Paginación automática basada en parámetros del request
    }
}

