<?php

namespace App\Containers\Monitoring\ChildDevelopment\Actions;

use App\Containers\Monitoring\ChildDevelopment\Tasks\DeleteChildDevelopmentTask;
use App\Containers\Monitoring\ChildDevelopment\UI\API\Requests\DeleteChildDevelopmentRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class DeleteChildDevelopmentAction extends ParentAction
{
    public function __construct(
        private readonly DeleteChildDevelopmentTask $deleteChildDevelopmentTask,
    ) {
    }

    public function run(DeleteChildDevelopmentRequest $request): bool
    {
        return $this->deleteChildDevelopmentTask->run($request->id);
    }
}
