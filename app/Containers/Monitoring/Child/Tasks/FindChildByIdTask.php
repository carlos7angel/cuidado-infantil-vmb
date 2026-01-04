<?php

namespace App\Containers\Monitoring\Child\Tasks;

use App\Containers\Monitoring\Child\Data\Repositories\ChildRepository;
use App\Containers\Monitoring\Child\Events\ChildRequested;
use App\Containers\Monitoring\Child\Models\Child;
use App\Containers\Monitoring\Child\UI\API\Requests\FindChildByIdRequest;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class FindChildByIdTask extends ParentTask
{
    public function __construct(
        private readonly ChildRepository $repository,
    ) {
    }

    public function run($id): Child
    {
        $child = $this->repository->findOrFail($id);

        ChildRequested::dispatch($child);

        return $child;
    }
}
