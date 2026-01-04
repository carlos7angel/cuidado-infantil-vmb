<?php

namespace App\Containers\Monitoring\ChildDevelopment\Data\Repositories;

use App\Containers\Monitoring\ChildDevelopment\Models\ChildDevelopmentEvaluation;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of ChildDevelopmentEvaluation
 *
 * @extends ParentRepository<TModel>
 *
 * @deprecated Use ChildDevelopmentEvaluationRepository instead
 * This repository is kept for backward compatibility with existing code.
 * It will be removed in a future version.
 */
final class ChildDevelopmentRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'child_id' => '=',
        'evaluation_date' => '=',
        'age_months' => '=',
    ];

    public function model(): string
    {
        return ChildDevelopmentEvaluation::class;
    }
}
