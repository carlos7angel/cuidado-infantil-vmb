<?php

namespace App\Containers\Monitoring\ChildcareCenter\Data\Repositories;

use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of ChildcareCenter
 *
 * @extends ParentRepository<TModel>
 */
final class ChildcareCenterRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}
