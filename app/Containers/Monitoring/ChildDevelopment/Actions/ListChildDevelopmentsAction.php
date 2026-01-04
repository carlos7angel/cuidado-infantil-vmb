<?php

namespace App\Containers\Monitoring\ChildDevelopment\Actions;

use App\Containers\Monitoring\ChildDevelopment\Tasks\ListChildDevelopmentsTask;
use App\Containers\Monitoring\ChildDevelopment\UI\API\Requests\ListChildDevelopmentsRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListChildDevelopmentsAction extends ParentAction
{
    public function __construct(
        private readonly ListChildDevelopmentsTask $listChildDevelopmentsTask,
    ) {
    }

    public function run(ListChildDevelopmentsRequest $request): mixed
    {
        return $this->listChildDevelopmentsTask->run();
    }
}
