<?php

namespace App\Containers\Monitoring\Child\Actions;

use App\Containers\Monitoring\Child\Models\Child;
use App\Containers\Monitoring\Child\Reports\Exports\ChildSummaryReportExport;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

final class GenerateChildSummaryReportAction extends ParentAction
{
    public function run(Child $child): mixed
    {
        $childName = Str::slug($child->full_name);
        $filename = 'Reporte_Infante_' . $childName . '_' . now()->format('Y-m-d') . '.xlsx';
        
        return Excel::download(new ChildSummaryReportExport($child), $filename);
    }
}

