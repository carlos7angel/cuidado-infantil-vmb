<?php

namespace App\Containers\Monitoring\Child\Actions;

use App\Containers\Monitoring\Child\Reports\Exports\ChildrenReportExport;
use App\Ship\Parents\Actions\Action as ParentAction;
use Maatwebsite\Excel\Facades\Excel;

final class GenerateChildrenReportAction extends ParentAction
{
    public function run(): mixed
    {
        $filename = 'Reporte_Infantes_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new ChildrenReportExport(), $filename);
    }
}

