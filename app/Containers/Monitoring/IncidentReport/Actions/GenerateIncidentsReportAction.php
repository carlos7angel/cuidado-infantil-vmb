<?php

namespace App\Containers\Monitoring\IncidentReport\Actions;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Ship\Parents\Actions\Action as ParentAction;
use App\Containers\Monitoring\IncidentReport\Reports\Exports\IncidentsReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Containers\Monitoring\Child\Models\User;

final class GenerateIncidentsReportAction extends ParentAction
{
    public function run(): mixed
    {
        /** @var User $user */
        $user = Auth::user();
        $childcareCenterId = null;

        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            $childcareCenterId = $user->childcare_center_id;
        }

        $filename = 'Reporte_Incidentes_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        $export = new IncidentsReportExport($childcareCenterId);
        $result = Excel::download($export, $filename);
        return $result;
    }
}
