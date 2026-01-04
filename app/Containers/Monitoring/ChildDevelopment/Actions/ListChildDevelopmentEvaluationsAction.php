<?php

namespace App\Containers\Monitoring\ChildDevelopment\Actions;

use App\Containers\Monitoring\ChildDevelopment\Tasks\ListChildDevelopmentEvaluationsTask;
use App\Containers\Monitoring\ChildDevelopment\UI\API\Requests\ListChildDevelopmentEvaluationsRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListChildDevelopmentEvaluationsAction extends ParentAction
{
    public function __construct(
        private readonly ListChildDevelopmentEvaluationsTask $listChildDevelopmentEvaluationsTask,
    ) {
    }

    public function run(ListChildDevelopmentEvaluationsRequest $request): mixed
    {
        // La paginación se maneja automáticamente por addRequestCriteria() en el Task
        return $this->listChildDevelopmentEvaluationsTask->run($request->child_id);
    }
}

