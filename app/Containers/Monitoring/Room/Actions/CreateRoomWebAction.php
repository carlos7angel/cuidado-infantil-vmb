<?php

namespace App\Containers\Monitoring\Room\Actions;

use App\Containers\Monitoring\Room\Models\Room;
use App\Containers\Monitoring\Room\Tasks\CreateRoomTask;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Http\Request;

final class CreateRoomWebAction extends ParentAction
{
    public function __construct(
        private readonly CreateRoomTask $createRoomTask,
    ) {
    }

    public function run(Request $request): Room
    {
        $data = $this->prepareData($request);

        return $this->createRoomTask->run($data);
    }

    private function prepareData(Request $request): array
    {
        $data = $request->only([
            'childcare_center_id',
            'name',
            'description',
            'capacity',
            'is_active',
        ]);

        // Convert is_active to boolean
        if (isset($data['is_active'])) {
            $data['is_active'] = filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN);
        } else {
            $data['is_active'] = true;
        }

        return $data;
    }
}

