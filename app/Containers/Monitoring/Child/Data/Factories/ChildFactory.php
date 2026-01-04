<?php

namespace App\Containers\Monitoring\Child\Data\Factories;

use App\Containers\Monitoring\Child\Models\Child;
use App\Ship\Parents\Factories\Factory as ParentFactory;

/**
 * @template TModel of Child
 *
 * @extends ParentFactory<TModel>
 */
final class ChildFactory extends ParentFactory
{
    /** @var class-string<TModel> */
    protected $model = Child::class;

    public function definition(): array
    {
        return [];
    }
}
