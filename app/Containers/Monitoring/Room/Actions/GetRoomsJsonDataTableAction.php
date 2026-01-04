<?php

namespace App\Containers\Monitoring\Room\Actions;

use App\Containers\Frontend\Administrator\UI\WEB\Requests\Room\GetRoomsJsonDataTableRequest;
use App\Containers\Monitoring\Room\Tasks\GetRoomsJsonDataTableTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetRoomsJsonDataTableAction extends ParentAction
{
    public function __construct(
        private readonly GetRoomsJsonDataTableTask $getRoomsJsonDataTableTask,
    ) {
    }

    public function run(GetRoomsJsonDataTableRequest $request): mixed
    {
        return $this->getRoomsJsonDataTableTask->run($request);
    }
}

