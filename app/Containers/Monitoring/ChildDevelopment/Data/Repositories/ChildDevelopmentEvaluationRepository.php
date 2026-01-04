<?php

namespace App\Containers\Monitoring\ChildDevelopment\Data\Repositories;

use App\Containers\Monitoring\ChildDevelopment\Models\ChildDevelopmentEvaluation;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of ChildDevelopmentEvaluation
 *
 * @extends ParentRepository<TModel>
 */
final class ChildDevelopmentEvaluationRepository extends ParentRepository
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

