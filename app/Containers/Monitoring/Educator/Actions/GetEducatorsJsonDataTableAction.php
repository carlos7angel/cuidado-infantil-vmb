<?php

namespace App\Containers\Monitoring\Educator\Actions;

use App\Containers\Frontend\Administrator\UI\WEB\Requests\Educator\GetEducatorsJsonDataTableRequest;
use App\Containers\Monitoring\Educator\Tasks\GetEducatorsJsonDataTableTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetEducatorsJsonDataTableAction extends ParentAction
{
    public function __construct(
        private readonly GetEducatorsJsonDataTableTask $getEducatorsJsonDataTableTask,
    ) {
    }

    public function run(GetEducatorsJsonDataTableRequest $request): mixed
    {
        return $this->getEducatorsJsonDataTableTask->run($request);
    }
}

