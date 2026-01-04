<?php

namespace App\Containers\AppSection\File\Tasks;

use App\Containers\AppSection\File\Data\Repositories\FileRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class DeleteFileTask extends ParentTask
{
    public function __construct(
        private readonly FileRepository $repository,
    ) {
    }

    public function run($id): bool
    {
        return $this->repository->delete($id);
    }
}
