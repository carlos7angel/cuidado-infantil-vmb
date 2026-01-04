<?php

namespace App\Containers\AppSection\Authorization\Data\Seeders;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\AppSection\Authorization\Models\Role as RoleModel;
use App\Containers\AppSection\User\Tasks\CreateUserTask;
use App\Ship\Parents\Seeders\Seeder as ParentSeeder;

final class SuperAdminSeeder_2 extends ParentSeeder
{
    public function run(CreateUserTask$createUserTask): void
    {
        $user_super_admin = $createUserTask->run([
            'email' => 'admin@admin.com',
            'password' => 'admin123',
            'name' => 'Super Admin',
        ]);
        $role_super_admin = RoleModel::findByName(Role::SUPER_ADMIN->value, 'web');
        $user_super_admin->assignRole($role_super_admin);
        $user_super_admin->markEmailAsVerified();
        $user_super_admin->save();

        $user_educator = $createUserTask->run([
            'email' => 'educator@educator.com',
            'password' => 'educator123',
            'name' => 'Educator',
        ]);
        $role_educator = RoleModel::findByName(Role::EDUCATOR->value, 'api');
        $user_educator->assignRole($role_educator);
        $user_educator->markEmailAsVerified();
        $user_educator->save();

    }
}
