<?php

namespace App\Containers\AppSection\User\UI\API\Transformers;

use App\Containers\AppSection\Authorization\UI\API\Transformers\PermissionTransformer;
use App\Containers\AppSection\Authorization\UI\API\Transformers\RoleTransformer;
use App\Containers\AppSection\User\Models\User;
use App\Containers\Monitoring\ChildcareCenter\UI\API\Transformers\ChildcareCenterTransformer;
use App\Containers\Monitoring\Educator\UI\API\Transformers\EducatorTransformer;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\NullResource;

class UserTransformer extends ParentTransformer
{
    protected array $availableIncludes = [
        'roles',
        'permissions',
        'educator',
        'childcareCenters',
    ];

    protected array $defaultIncludes = [];

    public function transform(User $user): array
    {
        return [
            'type' => $user->getResourceKey(),
            'id' => $user->getHashedKey(),
            'name' => $user->name,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'is_educator' => $user->isEducator(),
        ];
    }

    public function includeRoles(User $user): Collection
    {
        return $this->collection($user->roles, new RoleTransformer());
    }

    public function includePermissions(User $user): Collection
    {
        return $this->collection($user->permissions, new PermissionTransformer());
    }

    /**
     * Include educator profile (1:1 relationship).
     */
    public function includeEducator(User $user): Item|NullResource
    {
        $educator = $user->educator;

        if (!$educator) {
            return $this->null();
        }

        return $this->item($educator, new EducatorTransformer());
    }

    /**
     * Include childcare centers assigned to the educator.
     */
    public function includeChildcareCenters(User $user): Collection|NullResource
    {
        $educator = $user->educator;

        if (!$educator) {
            return $this->null();
        }

        return $this->collection($educator->childcareCenters, new ChildcareCenterTransformer());
    }
}
