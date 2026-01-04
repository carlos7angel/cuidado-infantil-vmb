<?php

namespace App\Containers\AppSection\User\Actions;

use App\Containers\AppSection\User\Tasks\GetAdminUsersJsonDataTableTask;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Http\Request;

final class GetAdminUsersJsonDataTableAction extends ParentAction
{
    public function __construct(
        private readonly GetAdminUsersJsonDataTableTask $getAdminUsersJsonDataTableTask,
    ) {
    }

    public function run(Request $request): mixed
    {
        return $this->getAdminUsersJsonDataTableTask->run($request);
    }
}
