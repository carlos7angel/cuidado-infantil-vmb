<?php

namespace App\Containers\Monitoring\ChildEnrollment\Data\Factories;

use App\Containers\Monitoring\ChildEnrollment\Models\ChildEnrollment;
use App\Ship\Parents\Factories\Factory as ParentFactory;

/**
 * @template TModel of ChildEnrollment
 *
 * @extends ParentFactory<TModel>
 */
final class ChildEnrollmentFactory extends ParentFactory
{
    /** @var class-string<TModel> */
    protected $model = ChildEnrollment::class;

    public function definition(): array
    {
        return [];
    }
}
