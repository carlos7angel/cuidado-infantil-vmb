<?php

namespace App\Containers\Monitoring\Room\Tasks;

use App\Containers\Monitoring\Room\Data\Repositories\RoomRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Database\Eloquent\Collection;

final class ListRoomsByChildcareCenterTask extends ParentTask
{
    public function __construct(
        private readonly RoomRepository $repository,
    ) {
    }

    public function run($childcareCenterId): Collection
    {
        return $this->repository->findWhere(['childcare_center_id' => $childcareCenterId]);
    }
}
