<?php

namespace App\Containers\Monitoring\Child\Data\Repositories;

use App\Containers\Monitoring\Child\Models\Child;
use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Containers\Monitoring\ChildEnrollment\Enums\EnrollmentStatus;
use App\Ship\Parents\Repositories\Repository as ParentRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * @template TModel of Child
 *
 * @extends ParentRepository<TModel>
 */
final class ChildRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];

    /**
     * Get all active children enrolled in a specific childcare center.
     * Optimized query starting from child_enrollments (filtered table) for better performance.
     * Uses whereIn with subquery - more efficient because we filter child_enrollments first,
     * then get only the matching children. This is faster than JOIN when the filtered
     * enrollment table is smaller than the children table.
     * Only selects necessary columns to minimize data transfer.
     */
    public function findActiveByChildcareCenter(ChildcareCenter $childcareCenter): Collection
    {
        $childTable = $this->model->getTable();

        // Start from child_enrollments (filtered by center and status)
        // then get children using whereIn - more efficient for filtered datasets
        return $this->model
            ->select([
                "{$childTable}.id",
                "{$childTable}.first_name",
                "{$childTable}.paternal_last_name",
                "{$childTable}.maternal_last_name",
                "{$childTable}.birth_date",
                "{$childTable}.gender",
                "{$childTable}.created_at",
                "{$childTable}.updated_at",
            ])
            ->whereIn("{$childTable}.id", function ($query) use ($childcareCenter) {
                $query->select('child_id')
                    ->from('child_enrollments')
                    ->where('childcare_center_id', $childcareCenter->id)
                    ->where('status', EnrollmentStatus::ACTIVE->value);
            })
            ->get();
    }
}
