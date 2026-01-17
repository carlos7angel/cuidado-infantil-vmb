<?php

namespace App\Containers\Monitoring\Educator\Actions;

use App\Ship\Parents\Actions\Action as ParentAction;
use App\Containers\Monitoring\Educator\Reports\Exports\EducatorsReportExport;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

final class GenerateEducatorsReportAction extends ParentAction
{
    public function run(): mixed
    {
        $filename = 'Reporte_Educadores_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new EducatorsReportExport(), $filename);
    }
}
