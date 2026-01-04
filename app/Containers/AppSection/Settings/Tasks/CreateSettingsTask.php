<?php

namespace App\Containers\AppSection\Settings\Tasks;

use App\Containers\AppSection\Settings\Data\Repositories\SettingsRepository;
use App\Containers\AppSection\Settings\Models\Settings;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateSettingsTask extends ParentTask
{
    public function __construct(
        private readonly SettingsRepository $repository,
    ) {
    }

    public function run(array $data): Settings
    {
        return $this->repository->create($data);
    }
}
