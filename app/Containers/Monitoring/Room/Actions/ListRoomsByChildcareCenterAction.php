<?php

namespace App\Containers\Monitoring\Room\Actions;

use App\Containers\Monitoring\Room\Tasks\ListRoomsByChildcareCenterTask;
use App\Containers\Monitoring\Room\UI\API\Requests\ListRoomsByChildcareCenterRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListRoomsByChildcareCenterAction extends ParentAction
{
    public function __construct(
        private readonly ListRoomsByChildcareCenterTask $listRoomsByChildcareCenterTask,
    ) {
    }

    public function run(ListRoomsByChildcareCenterRequest $request): mixed
    {
        return $this->listRoomsByChildcareCenterTask->run($request->childcare_center_id);
    }
}