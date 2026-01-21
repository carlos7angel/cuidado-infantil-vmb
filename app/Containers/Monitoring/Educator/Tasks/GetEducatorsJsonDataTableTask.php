<?php

namespace App\Containers\Monitoring\Educator\Tasks;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Educator\GetEducatorsJsonDataTableRequest;
use App\Containers\Monitoring\Educator\Data\Repositories\EducatorRepository;
use App\Ship\Criteria\OrderByFieldCriteria;
use App\Ship\Criteria\SkipTakeCriteria;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\Auth;

final class GetEducatorsJsonDataTableTask extends ParentTask
{
    public function __construct(
        private readonly EducatorRepository $repository,
    ) {
    }

    public function run(GetEducatorsJsonDataTableRequest $request): mixed
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
        $searchFieldEmail = $requestData['columns'][2]['search']['value'] ?? '';
        $searchFieldState = $requestData['columns'][3]['search']['value'] ?? '';

        // Get total count before any filtering (recordsTotal)
        /** @var User $user */
        $user = Auth::user();
        
        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            $recordsTotal = $this->repository->whereHas('childcareCenters', function ($q) use ($user) {
                $q->where('childcare_centers.id', $user->childcare_center_id);
            })->count();
        } else {
            $recordsTotal = $this->repository->count();
        }

        $result = $this->repository->scopeQuery(function ($query) use (
            $searchValue,
            $searchFieldName,
            $searchFieldEmail,
            $searchFieldState,
            $user,
        ) {
            if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
                $query = $query->whereHas('childcareCenters', function ($q) use ($user) {
                    $q->where('childcare_centers.id', $user->childcare_center_id);
                });
            }

            // Filter by name
            if (!empty($searchFieldName)) {
                $query = $query->where(function ($q) use ($searchFieldName) {
                    $q->where('first_name', 'like', '%'.$searchFieldName.'%')
                      ->orWhere('last_name', 'like', '%'.$searchFieldName.'%');
                });
            }

            // Filter by email (from user relationship)
            if (!empty($searchFieldEmail)) {
                $query = $query->whereHas('user', function ($q) use ($searchFieldEmail) {
                    $q->where('email', 'like', '%'.$searchFieldEmail.'%');
                });
            }

            // Filter by state
            if (!empty($searchFieldState)) {
                $query = $query->where('state', 'like', '%'.$searchFieldState.'%');
            }

            // Global search across multiple fields
            if (!empty($searchValue)) {
                $query = $query->where(function ($q) use ($searchValue) {
                    $q->where('first_name', 'like', '%'.$searchValue.'%')
                      ->orWhere('last_name', 'like', '%'.$searchValue.'%')
                      ->orWhere('dni', 'like', '%'.$searchValue.'%')
                      ->orWhere('phone', 'like', '%'.$searchValue.'%')
                      ->orWhere('state', 'like', '%'.$searchValue.'%')
                      ->orWhereHas('user', function ($subQ) use ($searchValue) {
                          $subQ->where('email', 'like', '%'.$searchValue.'%');
                      });
                });
            }

            return $query;
        });

        // Get filtered count before pagination (recordsFiltered)
        $recordsFiltered = (clone $result)->count();
        
        // Apply sorting
        if ($sortColumn != null && $sortColumn != "" && $sortColumnDir != null && $sortColumnDir != "") {
            if ($sortColumn === 'created_at') {
                $orderByColumn = 'created_at';
            } elseif (in_array($sortColumn, ['first_name', 'email', 'state'], true)) {
                $orderByColumn = $sortColumn === 'email' ? 'email' : $sortColumn;
            } else {
                $orderByColumn = 'first_name';
            }

            $result = $result->pushCriteria(new OrderByFieldCriteria($orderByColumn, $sortColumnDir));
        } else {
            // Default sorting by created_at desc
            $result = $result->pushCriteria(new OrderByFieldCriteria('created_at', 'desc'));
        }
        
        // Apply pagination
        $result = $result->pushCriteria(new SkipTakeCriteria($skip, $pageSize));

        // Get filtered data with relationships
        $data = $result->with(['user', 'childcareCenters'])->all();

        // Transform data for DataTables
        $transformedData = [];
        foreach ($data as $item) {
            $centers = $item->childcareCenters->pluck('name')->join(', ') ?: '-';
            $user = $item->user;

            $transformedData[] = [
                'id' => $item->id,
                'full_name' => $item->first_name . ' ' . $item->last_name,
                'email' => $user ? $user->email : '-',
                'state' => $item->state ?? '-',
                'childcare_centers' => $centers,
                'status' => $user && $user->active ? 'Activo' : 'Inactivo',
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

