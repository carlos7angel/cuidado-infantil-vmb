<?php

namespace App\Containers\Monitoring\Educator\Tasks;

use App\Containers\Monitoring\Educator\Data\Repositories\EducatorRepository;
use App\Containers\Monitoring\Educator\Events\EducatorUpdated;
use App\Containers\Monitoring\Educator\Models\Educator;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class UpdateEducatorTask extends ParentTask
{
    public function __construct(
        private readonly EducatorRepository $repository,
    ) {
    }

    public function run(array $data, $id): Educator
    {
        $educator = $this->repository->update($data, $id);

        EducatorUpdated::dispatch($educator);

        return $educator;
    }
}
