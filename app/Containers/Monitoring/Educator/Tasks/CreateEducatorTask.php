<?php

namespace App\Containers\Monitoring\Educator\Tasks;

use App\Containers\Monitoring\Educator\Data\Repositories\EducatorRepository;
use App\Containers\Monitoring\Educator\Events\EducatorCreated;
use App\Containers\Monitoring\Educator\Models\Educator;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateEducatorTask extends ParentTask
{
    public function __construct(
        private readonly EducatorRepository $repository,
    ) {
    }

    public function run(array $data): Educator
    {
        $educator = $this->repository->create($data);

        EducatorCreated::dispatch($educator);

        return $educator;
    }
}
