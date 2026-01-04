<?php

namespace App\Containers\Monitoring\Child\Tasks;

use App\Containers\Monitoring\Child\Data\Repositories\ChildFamilyMemberRepository;
use App\Containers\Monitoring\Child\Models\ChildFamilyMember;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateChildFamilyMemberTask extends ParentTask
{
    public function __construct(
        private readonly ChildFamilyMemberRepository $repository,
    ) {
    }

    public function run(array $data): ChildFamilyMember
    {
        return $this->repository->create($data);
    }
}