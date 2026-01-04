<?php

namespace App\Containers\Monitoring\IncidentReport\Data\Repositories;

use App\Containers\Monitoring\IncidentReport\Models\IncidentReport;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of IncidentReport
 *
 * @extends ParentRepository<TModel>
 */
final class IncidentReportRepository extends ParentRepository
{
    /**
     * Specify Model class name.
     */
    public function model(): string
    {
        return IncidentReport::class;
    }

    /**
     * @var array<string, string>
     */
    protected $fieldSearchable = [
        'id' => '=',
        'code' => 'like',
        'status' => '=',
        'child_id' => '=',
        'type' => '=',
        'severity_level' => '=',
        'incident_date' => '=',
        'reported_by' => '=',
        'childcare_center_id' => '=',
        'room_id' => '=',
    ];
}
