<?php

namespace App\Containers\Monitoring\Room\Actions;

use App\Containers\Monitoring\Room\Tasks\ListRoomsTask;
use App\Containers\Monitoring\Room\UI\WEB\Requests\ListRoomsRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListRoomsAction extends ParentAction
{
    public function __construct(
        private readonly ListRoomsTask $listRoomsTask,
    ) {
    }

    public function run(ListRoomsRequest $request): mixed
    {
        return $this->listRoomsTask->run();
    }
}
