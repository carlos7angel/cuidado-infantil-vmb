<?php

namespace App\Containers\Monitoring\ChildDevelopment\Actions;

use App\Containers\Monitoring\ChildDevelopment\Models\ChildDevelopmentEvaluation;
use App\Containers\Monitoring\ChildDevelopment\Tasks\FindChildDevelopmentByIdTask;
use App\Containers\Monitoring\ChildDevelopment\UI\API\Requests\FindChildDevelopmentByIdRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class FindChildDevelopmentByIdAction extends ParentAction
{
    public function __construct(
        private readonly FindChildDevelopmentByIdTask $findChildDevelopmentByIdTask,
    ) {
    }

    public function run(FindChildDevelopmentByIdRequest $request): ChildDevelopmentEvaluation
    {
        return $this->findChildDevelopmentByIdTask->run($request->id);
    }
}
