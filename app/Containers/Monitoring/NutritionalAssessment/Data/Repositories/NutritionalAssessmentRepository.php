<?php

namespace App\Containers\Monitoring\NutritionalAssessment\Data\Repositories;

use App\Containers\Monitoring\NutritionalAssessment\Models\NutritionalAssessment;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of NutritionalAssessment
 *
 * @extends ParentRepository<TModel>
 */
final class NutritionalAssessmentRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}
