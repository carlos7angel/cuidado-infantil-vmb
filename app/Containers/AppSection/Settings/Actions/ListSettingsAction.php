<?php

namespace App\Containers\AppSection\Settings\Actions;

use App\Containers\AppSection\Settings\Tasks\ListSettingsTask;
use App\Containers\AppSection\Settings\UI\API\Requests\ListSettingsRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListSettingsAction extends ParentAction
{
    public function __construct(
        private readonly ListSettingsTask $listSettingsTask,
    ) {
    }

    public function run(ListSettingsRequest $request): mixed
    {
        return $this->listSettingsTask->run();
    }
}
