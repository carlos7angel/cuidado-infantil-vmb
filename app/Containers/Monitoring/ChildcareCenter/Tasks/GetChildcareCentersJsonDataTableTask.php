<?php

namespace App\Containers\Monitoring\ChildcareCenter\Tasks;

use App\Containers\Frontend\Administrator\UI\WEB\Requests\ChildcareCenter\GetChildcareCentersJsonDataTableRequest;
use App\Containers\Monitoring\ChildcareCenter\Data\Repositories\ChildcareCenterRepository;
use App\Ship\Criteria\OrderByFieldCriteria;
use App\Ship\Criteria\SkipTakeCriteria;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class GetChildcareCentersJsonDataTableTask extends ParentTask
{
    public function __construct(
        private readonly ChildcareCenterRepository $repository,
    ) {
    }

    public function run(GetChildcareCentersJsonDataTableRequest $request): mixed
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
        $searchFieldState = $requestData['columns'][2]['search']['value'] ?? '';
        $searchFieldMunicipality = $requestData['columns'][3]['search']['value'] ?? '';

        // Get total count before any filtering (recordsTotal)
        $recordsTotal = $this->repository->count();

        $result = $this->repository->scopeQuery(function ($query) use (
            $searchValue,
            $searchFieldName,
            $searchFieldState,
            $searchFieldMunicipality,
        ) {
            // Filter by name
            if (!empty($searchFieldName)) {
                $query = $query->where('name', 'like', '%'.$searchFieldName.'%');
            }

            // Filter by state (exact match for dropdown)
            if (!empty($searchFieldState)) {
                $query = $query->where('state', '=', $searchFieldState);
            }

            // Filter by municipality
            if (!empty($searchFieldMunicipality)) {
                $query = $query->where('municipality', 'like', '%'.$searchFieldMunicipality.'%');
            }

            // Global search across multiple fields
            if (!empty($searchValue)) {
                $query = $query->where(function ($q) use ($searchValue) {
                    $q->where('name', 'like', '%'.$searchValue.'%')
                      ->orWhere('state', 'like', '%'.$searchValue.'%')
                      ->orWhere('city', 'like', '%'.$searchValue.'%')
                      ->orWhere('municipality', 'like', '%'.$searchValue.'%')
                      ->orWhere('phone', 'like', '%'.$searchValue.'%')
                      ->orWhere('email', 'like', '%'.$searchValue.'%');
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

        // Get filtered data
        $data = $result->all();

        // Transform data for DataTables
        $transformedData = [];
        foreach ($data as $item) {
            $transformedData[] = [
                'id' => $item->id,
                'name' => $item->name,
                'state' => $item->state,
                'municipality' => $item->municipality,
                'date_founded' => $item->date_founded ? $item->date_founded->format('d/m/Y') : '-',
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

