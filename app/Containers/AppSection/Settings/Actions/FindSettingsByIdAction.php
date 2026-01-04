<?php

namespace App\Containers\AppSection\Settings\Actions;

use App\Containers\AppSection\Settings\Models\Settings;
use App\Containers\AppSection\Settings\Tasks\FindSettingsByIdTask;
use App\Containers\AppSection\Settings\UI\API\Requests\FindSettingsByIdRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class FindSettingsByIdAction extends ParentAction
{
    public function __construct(
        private readonly FindSettingsByIdTask $findSettingsByIdTask,
    ) {
    }

    public function run(FindSettingsByIdRequest $request): Settings
    {
        return $this->findSettingsByIdTask->run($request->id);
    }
}
