<?php

namespace App\Containers\Monitoring\Educator\Data\Repositories;

use App\Containers\Monitoring\Educator\Models\Educator;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of Educator
 *
 * @extends ParentRepository<TModel>
 */
final class EducatorRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}
