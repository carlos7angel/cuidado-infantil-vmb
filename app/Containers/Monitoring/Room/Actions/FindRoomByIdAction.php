<?php

namespace App\Containers\Monitoring\Room\Actions;

use App\Containers\Monitoring\Room\Models\Room;
use App\Containers\Monitoring\Room\Tasks\FindRoomByIdTask;
use App\Containers\Monitoring\Room\UI\WEB\Requests\FindRoomByIdRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class FindRoomByIdAction extends ParentAction
{
    public function __construct(
        private readonly FindRoomByIdTask $findRoomByIdTask,
    ) {
    }

    public function run(FindRoomByIdRequest $request): Room
    {
        return $this->findRoomByIdTask->run($request->id);
    }
}
