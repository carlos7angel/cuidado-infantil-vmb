<?php

namespace App\Containers\Monitoring\Educator\Actions;

use App\Containers\Monitoring\Educator\Tasks\DeleteEducatorTask;
use App\Containers\Monitoring\Educator\UI\API\Requests\DeleteEducatorRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class DeleteEducatorAction extends ParentAction
{
    public function __construct(
        private readonly DeleteEducatorTask $deleteEducatorTask,
    ) {
    }

    public function run(DeleteEducatorRequest $request): bool
    {
        return $this->deleteEducatorTask->run($request->id);
    }
}
