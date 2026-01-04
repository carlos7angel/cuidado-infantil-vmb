<?php

namespace App\Containers\Monitoring\ChildcareCenter\Actions;

use App\Containers\Frontend\Administrator\UI\WEB\Requests\ChildcareCenter\GetChildcareCentersJsonDataTableRequest;
use App\Containers\Monitoring\ChildcareCenter\Tasks\GetChildcareCentersJsonDataTableTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetChildcareCentersJsonDataTableAction extends ParentAction
{
    public function __construct(
        private readonly GetChildcareCentersJsonDataTableTask $getChildcareCentersJsonDataTableTask,
    ) {
    }

    public function run(GetChildcareCentersJsonDataTableRequest $request): mixed
    {
        return $this->getChildcareCentersJsonDataTableTask->run($request);
    }
}
