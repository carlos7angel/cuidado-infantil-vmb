<?php

namespace App\Containers\Monitoring\Child\Tasks;

use App\Containers\Monitoring\Child\Data\Repositories\ChildRepository;
use App\Containers\Monitoring\Child\Events\ChildCreated;
use App\Containers\Monitoring\Child\Models\Child;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateChildTask extends ParentTask
{
    public function __construct(
        private readonly ChildRepository $repository,
    ) {
    }

    public function run(array $data): Child
    {
        $child = $this->repository->create($data);

        ChildCreated::dispatch($child);

        return $child;
    }
}
