<?php

namespace App\Containers\Monitoring\Educator\Actions;

use App\Containers\Monitoring\Educator\Tasks\ListEducatorsTask;
use App\Containers\Monitoring\Educator\UI\API\Requests\ListEducatorsRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListEducatorsAction extends ParentAction
{
    public function __construct(
        private readonly ListEducatorsTask $listEducatorsTask,
    ) {
    }

    public function run(ListEducatorsRequest $request): mixed
    {
        return $this->listEducatorsTask->run();
    }
}
