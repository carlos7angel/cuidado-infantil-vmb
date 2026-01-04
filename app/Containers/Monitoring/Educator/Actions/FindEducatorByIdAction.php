<?php

namespace App\Containers\Monitoring\Educator\Actions;

use App\Containers\Monitoring\Educator\Models\Educator;
use App\Containers\Monitoring\Educator\Tasks\FindEducatorByIdTask;
use App\Containers\Monitoring\Educator\UI\API\Requests\FindEducatorByIdRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class FindEducatorByIdAction extends ParentAction
{
    public function __construct(
        private readonly FindEducatorByIdTask $findEducatorByIdTask,
    ) {
    }

    public function run(FindEducatorByIdRequest $request): Educator
    {
        return $this->findEducatorByIdTask->run($request->id);
    }
}
