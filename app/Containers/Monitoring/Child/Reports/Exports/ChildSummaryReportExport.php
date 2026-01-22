<?php

namespace App\Containers\Monitoring\Child\Reports\Exports;

use App\Containers\Monitoring\Child\Models\Child;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ChildSummaryReportExport implements WithMultipleSheets
{
    use Exportable;

    protected Child $child;

    public function __construct(Child $child)
    {
        $this->child = $child;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            new NutritionalAssessmentsSheet($this->child),
            new VaccinationsSheet($this->child),
            new DevelopmentEvaluationsSheet($this->child),
            new IncidentsSheet($this->child),
        ];
    }
}

