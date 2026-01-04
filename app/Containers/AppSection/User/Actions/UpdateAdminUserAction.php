<?php

namespace App\Containers\AppSection\User\Actions;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\AppSection\User\Models\User;
use App\Containers\AppSection\User\Tasks\UpdateUserTask;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

final class UpdateAdminUserAction extends ParentAction
{
    public function __construct(
        private readonly UpdateUserTask $updateUserTask,
    ) {
    }

    public function run(Request $request): User
    {
        $userId = $request->input('user_id');
        $data = $this->prepareData($request);

        // Create user data - only include fields that are provided
        $userData = [];

        // Handle info updates (name, email, active, childcare_center_id)
        if ($data['name'] !== null) {
            $userData['name'] = $data['name'];
        }
        if ($data['email'] !== null) {
            $userData['email'] = $data['email'];
        }
        if ($data['active'] !== null) {
            $userData['active'] = $data['active'];
        }
        if ($data['childcare_center_id'] !== null) {
            $userData['childcare_center_id'] = $data['childcare_center_id'];
        }

        // Handle password change if provided
        if (!empty($data['new_password'])) {
            // For admin password resets, we don't verify current password
            // Only validate new password confirmation
            if ($data['new_password'] !== $data['confirm_password']) {
                throw new \Exception('La confirmación de contraseña no coincide');
            }

            $userData['password'] = Hash::make($data['new_password']);
        }

        // Update the user if there's data to update
        if (!empty($userData)) {
            $user = $this->updateUserTask->run($userId, $userData);
        } else {
            $user = app(\App\Containers\AppSection\User\Tasks\FindUserByIdTask::class)->run($userId);
        }

        // Update role if changed (only for full updates)
        if (isset($data['user_role']) && $data['user_role'] !== null) {
            $user->syncRoles([]); // Remove all existing roles
            $role = $data['user_role'] === 'municipal_admin' ? Role::MUNICIPAL_ADMIN : Role::CHILDCARE_ADMIN;
            $user->assignRole($role);
        }

        return $user;
    }

    private function prepareData(Request $request): array
    {
        return [
            'user_id' => $request->input('user_id'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'user_role' => $request->input('user_role'),
            'childcare_center_id' => $request->input('childcare_center_id'),
            'active' => $request->input('active') === '1',
            'current_password' => $request->input('current_password'),
            'new_password' => $request->input('new_password'),
            'confirm_password' => $request->input('confirm_password'),
        ];
    }
}
