<?php

namespace App\Containers\Monitoring\Child\Tasks;

use App\Containers\Monitoring\Child\Data\Repositories\ChildRepository;
use App\Containers\Monitoring\Child\Events\ChildUpdated;
use App\Containers\Monitoring\Child\Models\Child;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class UpdateChildTask extends ParentTask
{
    public function __construct(
        private readonly ChildRepository $repository,
    ) {
    }

    public function run(array $data, $id): Child
    {
        $child = $this->repository->update($data, $id);

        ChildUpdated::dispatch($child);

        return $child;
    }
}
