<?php

namespace App\Containers\Monitoring\ChildVaccination\Data\Factories;

use App\Containers\Monitoring\ChildVaccination\Models\ChildVaccination;
use App\Ship\Parents\Factories\Factory as ParentFactory;

/**
 * @template TModel of ChildVaccination
 *
 * @extends ParentFactory<TModel>
 */
final class ChildVaccinationFactory extends ParentFactory
{
    /** @var class-string<TModel> */
    protected $model = ChildVaccination::class;

    public function definition(): array
    {
        return [];
    }
}
