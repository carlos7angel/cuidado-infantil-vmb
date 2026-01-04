<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Controllers;

use App\Containers\AppSection\User\Actions\CreateAdminUserAction;
use App\Containers\AppSection\User\Actions\GetAdminUsersJsonDataTableAction;
use App\Containers\AppSection\User\Actions\UpdateAdminUserAction;
use App\Containers\AppSection\User\Tasks\FindUserByIdTask;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\User\EditUserRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\User\ListAdminUsersJsonDataTableRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\User\UpdateUserInfoRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\User\UpdateUserPasswordRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\User\UpdateUserRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\User\ShowUserRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\User\StoreUserRequest;
use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

final class UserController extends WebController
{
    public function listAdminUsers(): View
    {
        $page_title = 'Gestión de Usuarios Administradores';
        $childcare_centers = ChildcareCenter::orderBy('name')->get();

        return view('frontend@administrator::user.list', compact('page_title', 'childcare_centers'));
    }

    public function listAdminUsersJsonDataTable(ListAdminUsersJsonDataTableRequest $request, GetAdminUsersJsonDataTableAction $getAdminUsersJsonDataTableAction)
    {
        try {
            $data = $getAdminUsersJsonDataTableAction->run($request);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(ShowUserRequest $request): View
    {
        $page_title = 'Detalle del Usuario';
        $user = app(FindUserByIdTask::class)->run($request->user_id);
        $childcare_centers = ChildcareCenter::orderBy('name')->get();

        // Load necessary relationships
        $user->load(['roles', 'childcareCenter']);

        return view('frontend@administrator::user.show', compact('page_title', 'user', 'childcare_centers'));
    }

    public function edit(EditUserRequest $request): JsonResponse
    {
        try {
            $user = app(FindUserByIdTask::class)->run($request->user_id);

            // Load necessary relationships
            $user->load(['roles', 'childcareCenter']);

            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function updateInfo(UpdateUserInfoRequest $request, UpdateAdminUserAction $updateAdminUserAction): JsonResponse
    {
        try {
            // Prepare data for info update only
            $data = [
                'user_id' => $request->route('user_id'),
                'name' => $request->input('name'),
                'email' => $request->input('email'), // Keep current email
                'active' => $request->input('active') === '1',
                'childcare_center_id' => $request->input('childcare_center_id'),
                'user_role' => null, // Don't change role
                'current_password' => null,
                'new_password' => null,
                'confirm_password' => null,
            ];

            $user = $updateAdminUserAction->run($request->merge($data));

            return response()->json([
                'success' => true,
                'message' => 'Información del usuario actualizada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function updatePassword(UpdateUserPasswordRequest $request, UpdateAdminUserAction $updateAdminUserAction): JsonResponse
    {
        try {
            // Prepare data for password update only
            $data = [
                'user_id' => $request->route('user_id'),
                'name' => null, // Don't change name
                'email' => null, // Don't change email
                'active' => null, // Don't change status
                'childcare_center_id' => null, // Don't change center
                'user_role' => null, // Don't change role
                'current_password' => null, // Not required for admin reset
                'new_password' => $request->input('new_password'),
                'confirm_password' => $request->input('confirm_password'),
            ];

            $user = $updateAdminUserAction->run($request->merge($data));

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function store(StoreUserRequest $request, CreateAdminUserAction $createAdminUserAction): JsonResponse
    {
        try {
            $result = $createAdminUserAction->run($request);
            $user = $result['user'];
            $password = $result['password'];

            // TODO: Implement email sending if send_email is true

            return response()->json([
                'success' => true,
                'message' => 'Usuario administrador creado exitosamente.',
                'password' => $password,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}