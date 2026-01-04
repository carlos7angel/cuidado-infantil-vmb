<?php

namespace App\Containers\Monitoring\Child\Actions;

use App\Containers\Frontend\Administrator\UI\WEB\Requests\Child\GetChildrenJsonDataTableRequest;
use App\Containers\Monitoring\Child\Tasks\GetChildrenJsonDataTableTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetChildrenJsonDataTableAction extends ParentAction
{
    public function __construct(
        private readonly GetChildrenJsonDataTableTask $getChildrenJsonDataTableTask,
    ) {
    }

    public function run(GetChildrenJsonDataTableRequest $request): mixed
    {
        return $this->getChildrenJsonDataTableTask->run($request);
    }
}

