<?php

namespace App\Containers\Monitoring\Room\Tasks;

use App\Containers\Monitoring\Room\Data\Repositories\RoomRepository;
use App\Containers\Monitoring\Room\Models\Room;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class UpdateRoomTask extends ParentTask
{
    public function __construct(
        private readonly RoomRepository $repository,
    ) {
    }

    public function run(array $data, $id): Room
    {
        return $this->repository->update($data, $id);
    }
}

