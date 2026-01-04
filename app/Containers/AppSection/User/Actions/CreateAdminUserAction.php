<?php

namespace App\Containers\AppSection\User\Actions;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\AppSection\User\Events\AdminUserCreatedEvent;
use App\Containers\AppSection\User\Tasks\CreateUserTask;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

final class CreateAdminUserAction extends ParentAction
{
    public function __construct(
        private readonly CreateUserTask $createUserTask,
    ) {
    }

    public function run(Request $request): array
    {
        $data = $this->prepareData($request);

        // Get password from request (generated on frontend)
        $password = $request->input('password');
        if (empty($password)) {
            throw new \Exception('La contraseña es requerida');
        }

        // Create user data
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($password),
            'is_client' => false,
            'active' => true,
        ];

        // Add childcare_center_id only for childcare_admin role
        if ($data['user_role'] === 'childcare_admin') {
            if (isset($data['childcare_center_id']) && !empty($data['childcare_center_id'])) {
                $userData['childcare_center_id'] = $data['childcare_center_id'];
            } else {
                throw new \Exception('Centro de Cuidado Infantil es requerido para administradores de CCI');
            }
        }

        // Create the user
        $user = $this->createUserTask->run($userData);

        // Assign role to user
        $role = $data['user_role'] === 'municipal_admin' ? Role::MUNICIPAL_ADMIN : Role::CHILDCARE_ADMIN;
        $user->assignRole($role);

        // Disparar evento para envío de email (si está habilitado)
        AdminUserCreatedEvent::dispatch($user, $password, $data['send_email'] ?? false);

        return [
            'user' => $user,
            'password' => $password,
            'send_email' => $data['send_email'] ?? false,
        ];
    }

    private function prepareData(Request $request): array
    {
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'user_role' => $request->input('user_role'),
            'send_email' => $request->input('send_email') === 'on',
        ];

        // Only include childcare_center_id if it's not empty and role is childcare_admin
        $childcareCenterId = $request->input('childcare_center_id');
        if (!empty($childcareCenterId) && $request->input('user_role') === 'childcare_admin') {
            $data['childcare_center_id'] = $childcareCenterId;
        }

        return $data;
    }

}
