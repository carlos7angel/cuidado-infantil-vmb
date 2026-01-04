<?php

namespace App\Containers\Monitoring\ChildcareCenter\Tasks;

use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Containers\Monitoring\ChildEnrollment\Data\Criteria\ActiveEnrollmentsCriteria;
use App\Containers\Monitoring\ChildEnrollment\Data\Repositories\ChildEnrollmentRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Database\Eloquent\Collection;

final class ListActiveChildrenByChildcareCenterTask extends ParentTask
{
    public function __construct(
        private readonly ChildEnrollmentRepository $childEnrollmentRepository,
    ) {
    }

    public function run(ChildcareCenter $childcareCenter): Collection
    {
        $enrollments = $this->childEnrollmentRepository
                            ->pushCriteriaWith(
                                ActiveEnrollmentsCriteria::class, 
                                [
                                    'childcareCenterId' => $childcareCenter->id
                                ]
                            )
                            ->with('child')
                            ->get();

        // Load relationships after getting the collection
        // $enrollments->load([
        //     'child:id,first_name,paternal_last_name,maternal_last_name,birth_date,gender',
        //     // 'room:id,name'
        // ]);

        return $enrollments;
    }
}

