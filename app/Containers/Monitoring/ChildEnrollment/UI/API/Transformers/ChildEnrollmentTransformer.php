<?php

namespace App\Containers\Monitoring\ChildEnrollment\UI\API\Transformers;

use App\Containers\Monitoring\ChildEnrollment\Models\ChildEnrollment;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class ChildEnrollmentTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(ChildEnrollment $childenrollment): array
    {
        return [
            'type' => $childenrollment->getResourceKey(),
            'id' => $childenrollment->getHashedKey(),
            'created_at' => $childenrollment->created_at,
            'updated_at' => $childenrollment->updated_at,
            'readable_created_at' => $childenrollment->created_at->diffForHumans(),
            'readable_updated_at' => $childenrollment->updated_at->diffForHumans(),
        ];
    }
}
