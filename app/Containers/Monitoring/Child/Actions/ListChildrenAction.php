<?php

namespace App\Containers\Monitoring\Child\Actions;

use App\Containers\Monitoring\Child\Tasks\ListChildrenTask;
use App\Containers\Monitoring\Child\UI\API\Requests\ListChildrenRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListChildrenAction extends ParentAction
{
    public function __construct(
        private readonly ListChildrenTask $listChildrenTask,
    ) {
    }

    public function run(ListChildrenRequest $request): mixed
    {
        return $this->listChildrenTask->run();
    }
}
