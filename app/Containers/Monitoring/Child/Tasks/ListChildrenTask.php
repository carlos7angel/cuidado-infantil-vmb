<?php

namespace App\Containers\Monitoring\Child\Tasks;

use App\Containers\Monitoring\Child\Data\Repositories\ChildRepository;
use App\Containers\Monitoring\Child\Events\ChildrenListed;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class ListChildrenTask extends ParentTask
{
    public function __construct(
        private readonly ChildRepository $repository,
    ) {
    }

    public function run(): mixed
    {
        $result = $this->repository
            ->addRequestCriteria()
            ->with([
                'medicalRecord:id,child_id,has_insurance,weight,height,created_at,updated_at',
                'socialRecord:id,child_id,guardian_type,created_at,updated_at',
                'activeEnrollment:id,child_id,childcare_center_id,room_id,status,enrollment_date,created_at,updated_at',
                'files:id,filleable_id,filleable_type,name,mime_type,size,created_at,updated_at'
            ])
            ->paginate();

        ChildrenListed::dispatch($result);

        return $result;
    }
}
