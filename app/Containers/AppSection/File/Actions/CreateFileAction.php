<?php

namespace App\Containers\AppSection\File\Actions;

use App\Containers\AppSection\File\Models\File;
use App\Containers\AppSection\File\Tasks\CreateFileTask;
use App\Containers\AppSection\File\UI\API\Requests\CreateFileRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateFileAction extends ParentAction
{
    public function __construct(
        private readonly CreateFileTask $createFileTask,
    ) {
    }

    public function run(CreateFileRequest $request): File
    {
        $data = $request->sanitize([
            // add your request data here
        ]);

        return $this->createFileTask->run($data);
    }
}
