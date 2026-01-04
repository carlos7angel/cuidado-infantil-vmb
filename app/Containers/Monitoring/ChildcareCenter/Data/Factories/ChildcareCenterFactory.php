<?php

namespace App\Containers\Monitoring\ChildcareCenter\Data\Factories;

use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Ship\Parents\Factories\Factory as ParentFactory;

/**
 * @template TModel of ChildcareCenter
 *
 * @extends ParentFactory<TModel>
 */
final class ChildcareCenterFactory extends ParentFactory
{
    /** @var class-string<TModel> */
    protected $model = ChildcareCenter::class;

    public function definition(): array
    {
        return [];
    }
}
