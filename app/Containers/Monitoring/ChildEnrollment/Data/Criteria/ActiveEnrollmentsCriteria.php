<?php

namespace App\Containers\Monitoring\ChildEnrollment\Data\Criteria;

use App\Containers\Monitoring\ChildEnrollment\Enums\EnrollmentStatus;
use App\Ship\Parents\Criteria\Criteria as ParentCriteria;
use Prettus\Repository\Contracts\RepositoryInterface as PrettusRepositoryInterface;

class ActiveEnrollmentsCriteria extends ParentCriteria
{
    public function __construct(
        private readonly int $childcareCenterId,
    ) {
    }

    public function apply($model, PrettusRepositoryInterface $repository)
    {
        return $model->where('childcare_center_id', $this->childcareCenterId)
                    ->where('status', EnrollmentStatus::ACTIVE->value);
    }
}