<?php

namespace App\Containers\Monitoring\NutritionalAssessment\Tasks;

use App\Containers\Monitoring\NutritionalAssessment\Data\Repositories\NutritionalAssessmentRepository;
use App\Containers\Monitoring\NutritionalAssessment\Events\NutritionalAssessmentsListed;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class ListChildNutritionalAssessmentsTask extends ParentTask
{
    public function __construct(
        private readonly NutritionalAssessmentRepository $repository,
    ) {
    }

    public function run(int $childId): mixed
    {
        // Usar scopeQuery para aplicar filtros y ordenamiento antes de paginar
        $result = $this->repository
            ->scopeQuery(function ($query) use ($childId) {
                return $query
                    ->where('child_id', $childId)
                    ->with('child') // Cargar relación child para el transformer
                    ->orderBy('assessment_date', 'desc')
                    ->orderBy('created_at', 'desc'); // En caso de empate, usar created_at
            })
            ->addRequestCriteria()
            ->paginate();

        NutritionalAssessmentsListed::dispatch($result);

        return $result;
    }
}

