<?php

namespace App\Containers\Monitoring\Educator\Actions;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Ship\Parents\Actions\Action as ParentAction;
use App\Containers\Monitoring\Educator\Reports\Exports\EducatorsReportExport;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Containers\AppSection\User\Models\User;
use Carbon\Carbon;


final class GenerateEducatorsReportAction extends ParentAction
{
    public function run(): mixed
    {
        /** @var User $user */
        $user = Auth::user();
        $childcareCenterId = null;

        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            $childcareCenterId = $user->childcare_center_id;
        }

        $filename = 'Reporte_Educadores_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new EducatorsReportExport($childcareCenterId), $filename);
    }
}
