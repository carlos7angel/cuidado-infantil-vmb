<?php

namespace App\Containers\AppSection\ActivityLog\Data\Factories;

use App\Containers\AppSection\ActivityLog\Models\ActivityLog;
use App\Ship\Parents\Factories\Factory as ParentFactory;

/**
 * @template TModel of ActivityLog
 *
 * @extends ParentFactory<TModel>
 */
final class ActivityLogFactory extends ParentFactory
{
    /** @var class-string<TModel> */
    protected $model = ActivityLog::class;

    public function definition(): array
    {
        return [];
    }
}
