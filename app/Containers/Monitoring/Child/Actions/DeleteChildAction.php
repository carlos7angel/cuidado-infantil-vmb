<?php

namespace App\Containers\Monitoring\Child\Actions;

use App\Containers\Monitoring\Child\Tasks\DeleteChildTask;
use App\Containers\Monitoring\Child\UI\API\Requests\DeleteChildRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class DeleteChildAction extends ParentAction
{
    public function __construct(
        private readonly DeleteChildTask $deleteChildTask,
    ) {
    }

    public function run(DeleteChildRequest $request): bool
    {
        return $this->deleteChildTask->run($request->id);
    }
}
