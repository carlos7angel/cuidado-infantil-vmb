<?php

namespace App\Containers\AppSection\Settings\Actions;

use App\Containers\AppSection\Settings\Models\Settings;
use App\Containers\AppSection\Settings\Tasks\CreateSettingsTask;
use App\Containers\AppSection\Settings\UI\API\Requests\CreateSettingsRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateSettingsAction extends ParentAction
{
    public function __construct(
        private readonly CreateSettingsTask $createSettingsTask,
    ) {
    }

    public function run(CreateSettingsRequest $request): Settings
    {
        $data = $request->sanitize([
            // add your request data here
        ]);

        return $this->createSettingsTask->run($data);
    }
}
