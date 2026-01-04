<?php

namespace App\Containers\AppSection\Settings\Actions;

use App\Containers\AppSection\Settings\Tasks\DeleteSettingsTask;
use App\Containers\AppSection\Settings\UI\API\Requests\DeleteSettingsRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class DeleteSettingsAction extends ParentAction
{
    public function __construct(
        private readonly DeleteSettingsTask $deleteSettingsTask,
    ) {
    }

    public function run(DeleteSettingsRequest $request): bool
    {
        return $this->deleteSettingsTask->run($request->id);
    }
}
