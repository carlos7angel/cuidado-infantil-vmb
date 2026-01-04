<?php

namespace App\Containers\Monitoring\Room\Tasks;

use App\Containers\Monitoring\Room\Data\Repositories\RoomRepository;
use App\Containers\Monitoring\Room\Models\Room;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class ListRoomsByChildcareCenterTask extends ParentTask
{
    public function __construct(
        private readonly RoomRepository $repository,
    ) {
    }

    public function run(int $childcareCenterId): mixed
    {
        return $this->repository
            ->addRequestCriteria()
            ->where('childcare_center_id', $childcareCenterId)
            ->paginate();
    }
}