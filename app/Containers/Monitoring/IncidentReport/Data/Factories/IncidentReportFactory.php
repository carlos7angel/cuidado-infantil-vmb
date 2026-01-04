<?php

namespace App\Containers\Monitoring\IncidentReport\Data\Factories;

use App\Containers\Monitoring\IncidentReport\Models\IncidentReport;
use App\Ship\Parents\Factories\Factory as ParentFactory;

/**
 * @template TModel of IncidentReport
 *
 * @extends ParentFactory<TModel>
 */
final class IncidentReportFactory extends ParentFactory
{
    /** @var class-string<TModel> */
    protected $model = IncidentReport::class;

    public function definition(): array
    {
        return [];
    }
}
