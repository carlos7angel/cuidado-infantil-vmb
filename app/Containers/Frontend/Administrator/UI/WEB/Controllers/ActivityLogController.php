<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Controllers;

use App\Containers\AppSection\ActivityLog\Actions\GetActivityLogsJsonDataTableAction;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\ActivityLog\GetActivityLogsJsonDataTableRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\ActivityLog\ManageActivityLogsRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

final class ActivityLogController extends WebController
{
    public function manage(ManageActivityLogsRequest $request): View
    {
        $page_title = 'Logs de Auditoría';

        return view('frontend@administrator::activity_log.manage', compact('page_title'));
    }

    public function listJsonDataTable(GetActivityLogsJsonDataTableRequest $request, GetActivityLogsJsonDataTableAction $getActivityLogsJsonDataTableAction): JsonResponse
    {
        try {
            $data = $getActivityLogsJsonDataTableAction->run($request);

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

