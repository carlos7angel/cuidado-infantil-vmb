<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Controllers;

use App\Containers\Monitoring\Child\Actions\ImportChildrenAction;
use App\Containers\Monitoring\Child\Actions\PreviewChildrenImportAction;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Child\ImportChildrenRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Child\PreviewChildrenImportRequest;
use App\Containers\Monitoring\ChildcareCenter\Tasks\ListChildcareCentersTask;
use App\Containers\Monitoring\Room\Tasks\ListRoomsByChildcareCenterTask;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ImportChildrenController extends WebController
{
    public function showImportForm(): View
    {
        $page_title = 'Importar Infantes';
        $childcareCenters = app(ListChildcareCentersTask::class)->run();
        return view('frontend@administrator::child.import', compact('page_title', 'childcareCenters'));
    }

    public function getRoomsByCenter(Request $request, $childcare_center_id): JsonResponse
    {
        try {
            $rooms = app(ListRoomsByChildcareCenterTask::class)->run($childcare_center_id);
            
            // Transform to simple array for frontend
            $data = $rooms->map(function($room) {
                return [
                    'id' => $room->id,
                    'text' => $room->name // Select2 expects 'text'
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function preview(PreviewChildrenImportRequest $request): JsonResponse
    {
        try {
            $data = app(PreviewChildrenImportAction::class)->run($request);
            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function import(ImportChildrenRequest $request): JsonResponse
    {
        try {
            $result = app(ImportChildrenAction::class)->run($request);
            return response()->json([
                'success' => true, 
                'message' => 'Proceso completado', 
                'details' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
