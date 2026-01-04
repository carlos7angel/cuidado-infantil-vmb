<?php

namespace App\Containers\Monitoring\ChildVaccination\Data\Repositories;

use App\Containers\Monitoring\ChildVaccination\Models\ChildVaccination;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of ChildVaccination
 *
 * @extends ParentRepository<TModel>
 */
final class ChildVaccinationRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}
