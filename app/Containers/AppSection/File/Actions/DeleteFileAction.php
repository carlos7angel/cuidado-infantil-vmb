<?php

namespace App\Containers\AppSection\File\Actions;

use App\Containers\AppSection\File\Tasks\DeleteFileTask;
use App\Containers\AppSection\File\UI\API\Requests\DeleteFileRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class DeleteFileAction extends ParentAction
{
    public function __construct(
        private readonly DeleteFileTask $deleteFileTask,
    ) {
    }

    public function run(DeleteFileRequest $request): bool
    {
        return $this->deleteFileTask->run($request->id);
    }
}
