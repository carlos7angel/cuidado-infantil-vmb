<?php

namespace App\Containers\AppSection\Authorization\Data\Seeders;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\AppSection\Authorization\Tasks\CreateRoleTask;
use App\Ship\Parents\Seeders\Seeder as ParentSeeder;

final class AuthorizationSeeder_1 extends ParentSeeder
{
    public function run(CreateRoleTask $task): void
    {
        $task->run(Role::SUPER_ADMIN->value,Role::SUPER_ADMIN->description(),Role::SUPER_ADMIN->label(), 'web');
        $task->run(Role::MUNICIPAL_ADMIN->value,Role::MUNICIPAL_ADMIN->description(),Role::MUNICIPAL_ADMIN->label(), 'web');
        $task->run(Role::CHILDCARE_ADMIN->value,Role::CHILDCARE_ADMIN->description(),Role::CHILDCARE_ADMIN->label(), 'web');
        $task->run(Role::EDUCATOR->value,Role::EDUCATOR->description(),Role::EDUCATOR->label(), 'api');
    }
}
