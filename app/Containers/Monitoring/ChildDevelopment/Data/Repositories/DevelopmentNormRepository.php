<?php

namespace App\Containers\Monitoring\ChildDevelopment\Data\Repositories;

use App\Containers\Monitoring\ChildDevelopment\Models\DevelopmentNorm;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of DevelopmentNorm
 *
 * @extends ParentRepository<TModel>
 */
final class DevelopmentNormRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'area' => '=',
        'age_min_months' => '=',
        'age_max_months' => '=',
        'status' => '=',
    ];

    public function model(): string
    {
        return DevelopmentNorm::class;
    }
}

