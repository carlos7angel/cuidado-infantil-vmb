<?php

namespace App\Containers\Monitoring\Educator\Actions;

use App\Containers\Monitoring\Educator\Models\Educator;
use App\Containers\Monitoring\Educator\Tasks\CreateEducatorWebTask;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Http\Request;

final class CreateEducatorWebAction extends ParentAction
{
    public function __construct(
        private readonly CreateEducatorWebTask $createEducatorWebTask,
    ) {
    }

    public function run(Request $request): array
    {
        $data = $this->prepareData($request);

        return $this->createEducatorWebTask->run($data);
    }

    private function prepareData(Request $request): array
    {
        return $request->only([
            'email',
            'first_name',
            'last_name',
            'gender',
            'birth',
            'state',
            'dni',
            'phone',
            'contract_start_date',
            'contract_end_date',
            'childcare_center_ids',
        ]);
    }
}

