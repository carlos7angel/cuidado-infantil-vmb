<?php

namespace App\Containers\AppSection\Settings\Tasks;

use App\Containers\AppSection\Settings\Data\Repositories\SettingsRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class DeleteSettingsTask extends ParentTask
{
    public function __construct(
        private readonly SettingsRepository $repository,
    ) {
    }

    public function run($id): bool
    {
        return $this->repository->delete($id);
    }
}
