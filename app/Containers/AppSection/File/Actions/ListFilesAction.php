<?php

namespace App\Containers\AppSection\File\Actions;

use App\Containers\AppSection\File\Tasks\ListFilesTask;
use App\Containers\AppSection\File\UI\API\Requests\ListFilesRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListFilesAction extends ParentAction
{
    public function __construct(
        private readonly ListFilesTask $listFilesTask,
    ) {
    }

    public function run(ListFilesRequest $request): mixed
    {
        return $this->listFilesTask->run();
    }
}
