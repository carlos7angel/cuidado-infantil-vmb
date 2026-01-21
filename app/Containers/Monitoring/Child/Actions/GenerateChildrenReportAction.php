<?php

namespace App\Containers\Monitoring\Child\Actions;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\Monitoring\Child\Reports\Exports\ChildrenReportExport;
use App\Ship\Parents\Actions\Action as ParentAction;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

final class GenerateChildrenReportAction extends ParentAction
{
    public function run(): mixed
    {
        /** @var User $user */
        $user = Auth::user();
        $childcareCenterId = null;

        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            $childcareCenterId = $user->childcare_center_id;
        }

        $filename = 'Reporte_Infantes_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new ChildrenReportExport($childcareCenterId), $filename);
    }
}

