<?php

namespace App\Containers\AppSection\ActivityLog\Data\Repositories;

use App\Containers\AppSection\ActivityLog\Models\ActivityLog;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of ActivityLog
 *
 * @extends ParentRepository<TModel>
 */
final class ActivityLogRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}
