<?php

namespace App\Containers\Monitoring\Room\Tasks;

use App\Containers\Frontend\Administrator\UI\WEB\Requests\Room\GetRoomsJsonDataTableRequest;
use App\Containers\Monitoring\Room\Data\Repositories\RoomRepository;
use App\Ship\Criteria\OrderByFieldCriteria;
use App\Ship\Criteria\SkipTakeCriteria;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class GetRoomsJsonDataTableTask extends ParentTask
{
    public function __construct(
        private readonly RoomRepository $repository,
    ) {
    }

    public function run(GetRoomsJsonDataTableRequest $request): mixed
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

        // Advanced search fields from column search
        $searchFieldName = $requestData['columns'][1]['search']['value'] ?? '';
        $searchFieldChildcareCenter = $requestData['columns'][2]['search']['value'] ?? '';
        $searchFieldIsActive = $requestData['columns'][3]['search']['value'] ?? '';

        // Get total count before any filtering (recordsTotal)
        $recordsTotal = $this->repository->count();

        $result = $this->repository->scopeQuery(function ($query) use (
            $searchValue,
            $searchFieldName,
            $searchFieldChildcareCenter,
            $searchFieldIsActive,
        ) {
            // Filter by name
            if (!empty($searchFieldName)) {
                $query = $query->where('name', 'like', '%'.$searchFieldName.'%');
            }

            // Filter by childcare center
            if (!empty($searchFieldChildcareCenter)) {
                $query = $query->whereHas('childcareCenter', function ($q) use ($searchFieldChildcareCenter) {
                    $q->where('name', 'like', '%'.$searchFieldChildcareCenter.'%');
                });
            }

            // Filter by is_active
            if ($searchFieldIsActive !== '') {
                $query = $query->where('is_active', $searchFieldIsActive === '1' ? 1 : 0);
            }

            // Global search across multiple fields
            if (!empty($searchValue)) {
                $query = $query->where(function ($q) use ($searchValue) {
                    $q->where('name', 'like', '%'.$searchValue.'%')
                      ->orWhere('description', 'like', '%'.$searchValue.'%')
                      ->orWhereHas('childcareCenter', function ($subQ) use ($searchValue) {
                          $subQ->where('name', 'like', '%'.$searchValue.'%');
                      });
                });
            }

            return $query;
        });

        // Get filtered count before pagination (recordsFiltered)
        $recordsFiltered = (clone $result)->count();
        
        // Apply sorting
        if ($sortColumn != null && $sortColumn != "" && $sortColumnDir != null && $sortColumnDir != "") {
            $result = $result->pushCriteria(new OrderByFieldCriteria($sortColumn, $sortColumnDir));
        } else {
            // Default sorting by created_at desc
            $result = $result->pushCriteria(new OrderByFieldCriteria('created_at', 'desc'));
        }
        
        // Apply pagination
        $result = $result->pushCriteria(new SkipTakeCriteria($skip, $pageSize));

        // Get filtered data with relationships
        $data = $result->with('childcareCenter')->all();

        // Transform data for DataTables
        $transformedData = [];
        foreach ($data as $item) {
            $transformedData[] = [
                'id' => $item->id,
                'name' => $item->name,
                'childcare_center_name' => $item->childcareCenter ? $item->childcareCenter->name : '-',
                'is_active' => $item->is_active ? 'Activo' : 'Inactivo',
                'capacity' => $item->capacity,
                'description' => $item->description ?? '-',
                'created_at' => $item->created_at ? $item->created_at->format('d/m/Y') : '-',
            ];
        }

        return [
            'draw' => intval($draw),
            'recordsFiltered' => $recordsFiltered,
            'recordsTotal' => $recordsTotal,
            'data' => $transformedData
        ];
    }
}

