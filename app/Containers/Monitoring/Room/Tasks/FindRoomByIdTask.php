<?php

namespace App\Containers\Monitoring\Room\Tasks;

use App\Containers\Monitoring\Room\Data\Repositories\RoomRepository;
use App\Containers\Monitoring\Room\Models\Room;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class FindRoomByIdTask extends ParentTask
{
    public function __construct(
        private readonly RoomRepository $repository,
    ) {
    }

    public function run($id): Room
    {
        return $this->repository->findOrFail($id);
    }
}
