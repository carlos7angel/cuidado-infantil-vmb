<?php

namespace App\Containers\Monitoring\Educator\Actions;

use App\Containers\Monitoring\Educator\Models\Educator;
use App\Containers\Monitoring\Educator\Tasks\CreateEducatorTask;
use App\Containers\Monitoring\Educator\UI\API\Requests\CreateEducatorRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateEducatorAction extends ParentAction
{
    public function __construct(
        private readonly CreateEducatorTask $createEducatorTask,
    ) {
    }

    public function run(CreateEducatorRequest $request): Educator
    {
        $data = $request->sanitize([
            // add your request data here
        ]);

        return $this->createEducatorTask->run($data);
    }
}
