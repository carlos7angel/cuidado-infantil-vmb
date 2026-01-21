<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Controllers;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Room\FormRoomRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Room\GetRoomsJsonDataTableRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Room\ManageRoomsRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Room\StoreRoomRequest;
use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Containers\Monitoring\Room\Actions\CreateRoomWebAction;
use App\Containers\Monitoring\Room\Actions\GetRoomsJsonDataTableAction;
use App\Containers\Monitoring\Room\Actions\UpdateRoomWebAction;
use App\Containers\Monitoring\Room\Models\Room;
use App\Containers\Monitoring\Room\Tasks\FindRoomByIdTask;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

final class RoomController extends WebController
{
    public function manage(ManageRoomsRequest $request): View
    {
        $page_title = 'Gestión de Grupos/Salas';
        /** @var User $user */
        $user = Auth::user();

        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            $childcare_centers = ChildcareCenter::where('id', $user->childcare_center_id)->get();
        } else {
            $childcare_centers = ChildcareCenter::orderBy('name')->get();
        }

        return view('frontend@administrator::room.manage', compact('page_title', 'childcare_centers'));
    }

    public function listJsonDataTable(GetRoomsJsonDataTableRequest $request)
    {
        try {
            $data = app(GetRoomsJsonDataTableAction::class)->run($request);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function form_create(FormRoomRequest $request): View
    {
        $page_title = "Nuevo Grupo/Sala";
        $room = new Room();
        /** @var User $user */
        $user = Auth::user();

        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            $childcare_centers = ChildcareCenter::where('id', $user->childcare_center_id)->get();
        } else {
            $childcare_centers = ChildcareCenter::orderBy('name')->get();
        }

        return view('frontend@administrator::room.form', compact('page_title', 'room', 'childcare_centers'));
    }

    public function form_edit(FormRoomRequest $request): View
    {
        $page_title = "Editar Grupo/Sala";
        $room = app(FindRoomByIdTask::class)->run($request->id);
        /** @var User $user */
        $user = Auth::user();

        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            $childcare_centers = ChildcareCenter::where('id', $user->childcare_center_id)->get();
        } else {
            $childcare_centers = ChildcareCenter::orderBy('name')->get();
        }

        return view('frontend@administrator::room.form', compact('page_title', 'room', 'childcare_centers'));
    }

    public function store(StoreRoomRequest $request)
    {
        try {
            if ($request->has('id') && $request->id) {
                $room = app(UpdateRoomWebAction::class)->run($request, $request->id);
                $message = 'Grupo/Sala actualizado exitosamente';
            } else {
                $room = app(CreateRoomWebAction::class)->run($request);
                $message = 'Grupo/Sala creado exitosamente';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('admin.room.form_edit', ['id' => $room->id])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}

