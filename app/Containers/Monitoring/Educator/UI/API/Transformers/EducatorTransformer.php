<?php

namespace App\Containers\Monitoring\Educator\UI\API\Transformers;

use App\Containers\Monitoring\ChildcareCenter\UI\API\Transformers\ChildcareCenterTransformer;
use App\Containers\Monitoring\Educator\Models\Educator;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;
use League\Fractal\Resource\Collection;

final class EducatorTransformer extends ParentTransformer
{
    protected array $defaultIncludes = ['user'];

    protected array $availableIncludes = [
        'user',
        'childcareCenters',
    ];

    public function transform(Educator $educator): array
    {
        return [
            'type' => $educator->getResourceKey(),
            'id' => $educator->getHashedKey(),
            'first_name' => $educator->first_name,
            'last_name' => $educator->last_name,
            'full_name' => $educator->full_name,
            'gender' => $educator->gender?->value,
            'birth' => $educator->birth?->format('Y-m-d'),
            'birth_readable' => $educator->birth?->format('d/m/Y'),
            'state' => $educator->state,
            'dni' => $educator->dni,
            'phone' => $educator->phone,
            'state' => $educator->state,
            'created_at' => $educator->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $educator->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Include user relationship.
     */
    public function includeUser(Educator $educator)
    {
        if (!$educator->relationLoaded('user') || !$educator->user) {
            return null;
        }

        return $this->item($educator->user, new \App\Containers\AppSection\User\UI\API\Transformers\UserTransformer());
    }

    public function includeChildcareCenters(Educator $educator): Collection
    {
        return $this->collection($educator->childcareCenters, new ChildcareCenterTransformer());
    }
}
