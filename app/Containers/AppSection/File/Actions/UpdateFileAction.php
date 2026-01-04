<?php

namespace App\Containers\AppSection\File\Actions;

use App\Containers\AppSection\File\Models\File;
use App\Containers\AppSection\File\Tasks\UpdateFileTask;
use App\Containers\AppSection\File\UI\API\Requests\UpdateFileRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UpdateFileAction extends ParentAction
{
    public function __construct(
        private readonly UpdateFileTask $updateFileTask,
    ) {
    }

    public function run(UpdateFileRequest $request): File
    {
        $data = $request->sanitize([
            // add your request data here
        ]);

        return $this->updateFileTask->run($data, $request->id);
    }
}
