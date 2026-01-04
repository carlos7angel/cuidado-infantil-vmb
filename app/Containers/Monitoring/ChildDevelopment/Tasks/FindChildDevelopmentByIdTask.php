<?php

namespace App\Containers\Monitoring\ChildDevelopment\Tasks;

use App\Containers\Monitoring\ChildDevelopment\Data\Repositories\ChildDevelopmentEvaluationRepository;
use App\Containers\Monitoring\ChildDevelopment\Models\ChildDevelopmentEvaluation;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class FindChildDevelopmentByIdTask extends ParentTask
{
    public function __construct(
        private readonly ChildDevelopmentEvaluationRepository $repository,
    ) {
    }

    public function run($id): ChildDevelopmentEvaluation
    {
        $evaluation = $this->repository->findOrFail($id);

        // Cargar todas las relaciones necesarias para el detalle completo
        // No cargar scores.evaluation (relación circular), el transformer pasará age_months directamente
        $evaluation->load([
            'child',
            'assessedBy',
            'evaluationItems.developmentItem',
            'scores',
        ]);

        return $evaluation;
    }
}
