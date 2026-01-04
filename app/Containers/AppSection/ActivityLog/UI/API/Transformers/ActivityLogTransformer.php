<?php

namespace App\Containers\AppSection\ActivityLog\UI\API\Transformers;

use App\Containers\AppSection\ActivityLog\Models\ActivityLog;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class ActivityLogTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(ActivityLog $activitylog): array
    {
        return [
            'type' => $activitylog->getResourceKey(),
            'id' => $activitylog->getHashedKey(),
            'created_at' => $activitylog->created_at,
            'updated_at' => $activitylog->updated_at,
            'readable_created_at' => $activitylog->created_at->diffForHumans(),
            'readable_updated_at' => $activitylog->updated_at->diffForHumans(),
        ];
    }
}
