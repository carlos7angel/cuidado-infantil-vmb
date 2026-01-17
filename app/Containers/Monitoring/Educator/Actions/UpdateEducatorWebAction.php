<?php

namespace App\Containers\Monitoring\Educator\Actions;

use App\Containers\Monitoring\Educator\Models\Educator;
use App\Containers\Monitoring\Educator\Tasks\UpdateEducatorWebTask;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Http\Request;

final class UpdateEducatorWebAction extends ParentAction
{
    public function __construct(
        private readonly UpdateEducatorWebTask $updateEducatorWebTask,
    ) {
    }

    public function run(Request $request, int $id): Educator
    {
        $data = $this->prepareData($request);

        return $this->updateEducatorWebTask->run($data, $id);
    }

    private function prepareData(Request $request): array
    {
        return $request->only([
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

