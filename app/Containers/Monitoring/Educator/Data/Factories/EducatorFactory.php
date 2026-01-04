<?php

namespace App\Containers\Monitoring\Educator\Data\Factories;

use App\Containers\Monitoring\Educator\Models\Educator;
use App\Ship\Parents\Factories\Factory as ParentFactory;

/**
 * @template TModel of Educator
 *
 * @extends ParentFactory<TModel>
 */
final class EducatorFactory extends ParentFactory
{
    /** @var class-string<TModel> */
    protected $model = Educator::class;

    public function definition(): array
    {
        return [];
    }
}
