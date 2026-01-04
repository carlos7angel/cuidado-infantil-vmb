<?php

namespace App\Containers\Monitoring\Child\Actions;

use App\Containers\Monitoring\Child\Models\Child;
use App\Containers\Monitoring\Child\Tasks\FindChildByIdTask;
use App\Containers\Monitoring\Child\UI\API\Requests\FindChildByIdRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class FindChildByIdAction extends ParentAction
{
    public function __construct(
        private readonly FindChildByIdTask $findChildByIdTask,
    ) {
    }

    public function run(FindChildByIdRequest $request): Child
    {
        return $this->findChildByIdTask->run($request->id);
    }
}
