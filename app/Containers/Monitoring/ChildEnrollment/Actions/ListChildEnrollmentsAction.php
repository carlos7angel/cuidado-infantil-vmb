<?php

namespace App\Containers\Monitoring\ChildEnrollment\Actions;

use App\Containers\Monitoring\ChildEnrollment\Tasks\ListChildEnrollmentsTask;
use App\Containers\Monitoring\ChildEnrollment\UI\API\Requests\ListChildEnrollmentsRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListChildEnrollmentsAction extends ParentAction
{
    public function __construct(
        private readonly ListChildEnrollmentsTask $listChildEnrollmentsTask,
    ) {
    }

    public function run(ListChildEnrollmentsRequest $request): mixed
    {
        return $this->listChildEnrollmentsTask->run();
    }
}
