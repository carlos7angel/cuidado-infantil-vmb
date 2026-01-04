<?php

namespace App\Containers\Monitoring\Child\Tasks;

use App\Containers\Frontend\Administrator\UI\WEB\Requests\Child\GetChildrenJsonDataTableRequest;
use App\Containers\Monitoring\ChildEnrollment\Data\Repositories\ChildEnrollmentRepository;
use App\Ship\Criteria\OrderByFieldCriteria;
use App\Ship\Criteria\SkipTakeCriteria;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class GetChildrenJsonDataTableTask extends ParentTask
{
    public function __construct(
        private readonly ChildEnrollmentRepository $repository,
    ) {
    }

    public function run(GetChildrenJsonDataTableRequest $request): mixed
    {
        $requestData = $request->all();

        $draw = $requestData['draw'] ?? 1;
        $start = $requestData['start'] ?? 0;
        $length = $requestData['length'] ?? 10;
        $sortColumn = $sortColumnDir = null;
        
        if (isset($requestData['order']) && !empty($requestData['order'])) {
            $indexSort = $requestData['order'][0]['column'];
            $sortColumn = $requestData['columns'][$indexSort]['name'] ?? null;
            $sortColumnDir = $requestData['order'][0]['dir'] ?? 'asc';
        }
        
        $searchValue = $requestData['search']['value'] ?? '';
        $pageSize = $length != null ? intval($length) : 10;
        $skip = $start != null ? intval($start) : 0;

        // Filter by childcare center (from dropdown)
        $childcareCenterId = $request->input('childcare_center_id');

        // Advanced search fields from column search
        $searchFieldName = $requestData['columns'][1]['search']['value'] ?? '';
        $searchFieldStatus = $requestData['columns'][6]['search']['value'] ?? '';
        $searchFieldState = $requestData['columns'][4]['search']['value'] ?? '';

        // Get total count before any filtering (recordsTotal)
        $recordsTotal = $this->repository->count();

        $result = $this->repository->scopeQuery(function ($query) use (
            $searchValue,
            $searchFieldName,
            $searchFieldStatus,
            $searchFieldState,
            $childcareCenterId,
        ) {
            // Filter by childcare center (from dropdown)
            if (!empty($childcareCenterId)) {
                $query = $query->where('childcare_center_id', $childcareCenterId);
            }

            // Filter by child name
            if (!empty($searchFieldName)) {
                $query = $query->whereHas('child', function ($q) use ($searchFieldName) {
                    $q->where('first_name', 'like', '%'.$searchFieldName.'%')
                      ->orWhere('paternal_last_name', 'like', '%'.$searchFieldName.'%')
                      ->orWhere('maternal_last_name', 'like', '%'.$searchFieldName.'%');
                });
            }

            // Filter by enrollment status
            if (!empty($searchFieldStatus)) {
                $query = $query->where('status', $searchFieldStatus);
            }

            // Filter by state (from child)
            if (!empty($searchFieldState)) {
                $query = $query->whereHas('child', function ($q) use ($searchFieldState) {
                    $q->where('state', 'like', '%'.$searchFieldState.'%');
                });
            }

            // Global search across multiple fields
            if (!empty($searchValue)) {
                $query = $query->where(function ($q) use ($searchValue) {
                    $q->whereHas('child', function ($subQ) use ($searchValue) {
                        $subQ->where('first_name', 'like', '%'.$searchValue.'%')
                             ->orWhere('paternal_last_name', 'like', '%'.$searchValue.'%')
                             ->orWhere('maternal_last_name', 'like', '%'.$searchValue.'%')
                             ->orWhere('state', 'like', '%'.$searchValue.'%')
                             ->orWhere('city', 'like', '%'.$searchValue.'%');
                    })
                    ->orWhereHas('childcareCenter', function ($subQ) use ($searchValue) {
                        $subQ->where('name', 'like', '%'.$searchValue.'%');
                    })
                    ->orWhere('status', 'like', '%'.$searchValue.'%');
                });
            }

            return $query;
        });

        // Get filtered count before pagination (recordsFiltered)
        $recordsFiltered = (clone $result)->count();
        
        // Apply sorting
        if ($sortColumn != null && $sortColumn != "" && $sortColumnDir != null && $sortColumnDir != "") {
            // Handle sorting by related models
            if ($sortColumn === 'child_name') {
                // Sort by child name - we'll sort in memory for simplicity
                // Or use a join approach, but it's more complex with the repository pattern
                $result = $result->pushCriteria(new OrderByFieldCriteria('created_at', $sortColumnDir));
            } else {
                $result = $result->pushCriteria(new OrderByFieldCriteria($sortColumn, $sortColumnDir));
            }
        } else {
            // Default sorting by created_at desc
            $result = $result->pushCriteria(new OrderByFieldCriteria('created_at', 'desc'));
        }
        
        // Apply pagination
        $result = $result->pushCriteria(new SkipTakeCriteria($skip, $pageSize));

        // Get filtered data with relationships
        $data = $result->with(['child', 'childcareCenter', 'room'])->all();

        // Transform data for DataTables
        $transformedData = [];
        foreach ($data as $item) {
            $child = $item->child;
            $transformedData[] = [
                'id' => $item->id,
                'child_id' => $child ? $child->id : null,
                'full_name' => $child ? $child->full_name : '-',
                'initials' => $child ? $child->initials : '??',
                'age_readable' => $child ? $child->age_readable : '-',
                'childcare_center_name' => $item->childcareCenter ? $item->childcareCenter->name : '-',
                'status' => $item->status ? ucfirst($item->status->value) : '-',
                'state' => $child ? ($child->state ?? '-') : '-',
                'room_name' => $item->room ? $item->room->name : '-',
                'enrollment_date' => $item->enrollment_date ? $item->enrollment_date->format('d/m/Y') : '-',
            ];
        }

        // Sort by child_name if needed (after fetching data)
        if ($sortColumn === 'child_name' && $sortColumnDir) {
            usort($transformedData, function ($a, $b) use ($sortColumnDir) {
                $comparison = strcmp($a['full_name'], $b['full_name']);
                return $sortColumnDir === 'desc' ? -$comparison : $comparison;
            });
        }

        return [
            'draw' => intval($draw),
            'recordsFiltered' => $recordsFiltered,
            'recordsTotal' => $recordsTotal,
            'data' => $transformedData
        ];
    }
}

