<?php

namespace App\Containers\Monitoring\ChildEnrollment\Data\Repositories;

use App\Containers\Monitoring\ChildEnrollment\Models\ChildEnrollment;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of ChildEnrollment
 *
 * @extends ParentRepository<TModel>
 */
final class ChildEnrollmentRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}
