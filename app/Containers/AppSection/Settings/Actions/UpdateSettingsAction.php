<?php

namespace App\Containers\AppSection\Settings\Actions;

use App\Containers\AppSection\Settings\Models\Settings;
use App\Containers\AppSection\Settings\Tasks\UpdateSettingsTask;
use App\Containers\AppSection\Settings\UI\API\Requests\UpdateSettingsRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UpdateSettingsAction extends ParentAction
{
    public function __construct(
        private readonly UpdateSettingsTask $updateSettingsTask,
    ) {
    }

    public function run(UpdateSettingsRequest $request): Settings
    {
        $data = $request->sanitize([
            // add your request data here
        ]);

        return $this->updateSettingsTask->run($data, $request->id);
    }
}
