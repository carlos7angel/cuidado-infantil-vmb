<?php

namespace App\Containers\Monitoring\NutritionalAssessment\Data\Factories;

use App\Containers\Monitoring\NutritionalAssessment\Models\NutritionalAssessment;
use App\Ship\Parents\Factories\Factory as ParentFactory;

/**
 * @template TModel of NutritionalAssessment
 *
 * @extends ParentFactory<TModel>
 */
final class NutritionalAssessmentFactory extends ParentFactory
{
    /** @var class-string<TModel> */
    protected $model = NutritionalAssessment::class;

    public function definition(): array
    {
        return [];
    }
}
