<?php

namespace App\Containers\Monitoring\IncidentReport\Actions;

use App\Ship\Parents\Actions\Action as ParentAction;
use App\Containers\Monitoring\IncidentReport\Reports\Exports\IncidentsReportExport;
use Maatwebsite\Excel\Facades\Excel;

final class GenerateIncidentsReportAction extends ParentAction
{
    public function run(): mixed
    {
        $filename = 'Reporte_Incidentes_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        $export = new IncidentsReportExport();
        $result = Excel::download($export, $filename);
        return $result;
    }
}
