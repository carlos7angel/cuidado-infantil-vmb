<?php

namespace App\Containers\Monitoring\IncidentReport\Tasks;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\AppSection\User\Models\User;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Incident\GetIncidentsJsonDataTableRequest;
use App\Containers\Monitoring\IncidentReport\Data\Repositories\IncidentReportRepository;
use App\Ship\Criteria\OrderByFieldCriteria;
use App\Ship\Criteria\SkipTakeCriteria;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\Auth;

final class GetIncidentsJsonDataTableTask extends ParentTask
{
    public function __construct(
        private readonly IncidentReportRepository $repository,
    ) {
    }

    public function run(GetIncidentsJsonDataTableRequest $request): mixed
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
        $searchCode = $requestData['columns'][1]['search']['value'] ?? '';
        $searchChild = $requestData['columns'][2]['search']['value'] ?? '';
        $searchChildcareCenter = $requestData['columns'][3]['search']['value'] ?? '';
        
        // If user is childcare_admin, force scoping to their center
        /** @var User $user */
        $user = Auth::user();

        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            $searchChildcareCenter = $user->childcare_center_id;
        }

        $searchRoom = $request->input('kt_search_room') ?? '';
        $searchType = $requestData['columns'][4]['search']['value'] ?? '';
        $searchSeverity = $requestData['columns'][5]['search']['value'] ?? '';
        $searchStatus = $requestData['columns'][6]['search']['value'] ?? '';

        // Get total count before any filtering (recordsTotal)
        $recordsTotal = $this->repository->count();

        $result = $this->repository->scopeQuery(function ($query) use (
            $searchValue,
            $searchCode,
            $searchChild,
            $searchChildcareCenter,
            $searchRoom,
            $searchType,
            $searchSeverity,
            $searchStatus,
        ) {
            // Filter by code (exact match)
            if (!empty($searchCode)) {
                $query = $query->where('code', 'like', '%'.$searchCode.'%');
            }

            // Filter by child (name search)
            if (!empty($searchChild)) {
                $query = $query->whereHas('child', function ($q) use ($searchChild) {
                    $q->where('first_name', 'like', '%'.$searchChild.'%')
                      ->orWhere('paternal_last_name', 'like', '%'.$searchChild.'%')
                      ->orWhere('maternal_last_name', 'like', '%'.$searchChild.'%');
                });
            }

            // Filter by childcare center (exact match)
            if (!empty($searchChildcareCenter)) {
                $query = $query->where('childcare_center_id', '=', $searchChildcareCenter);
            }

            // Filter by room (exact match)
            if (!empty($searchRoom)) {
                $query = $query->where('room_id', '=', $searchRoom);
            }

            // Filter by type (exact match)
            if (!empty($searchType)) {
                $query = $query->where('type', '=', $searchType);
            }


            // Filter by status (exact match)
            if (!empty($searchStatus)) {
                $query = $query->where('status', '=', $searchStatus);
            }

            // Global search across multiple fields
            if (!empty($searchValue)) {
                $query = $query->where(function ($q) use ($searchValue) {
                    $q->where('code', 'like', '%'.$searchValue.'%')
                      ->orWhereHas('child', function ($childQuery) use ($searchValue) {
                          $childQuery->where('first_name', 'like', '%'.$searchValue.'%')
                                    ->orWhere('paternal_last_name', 'like', '%'.$searchValue.'%')
                                    ->orWhere('maternal_last_name', 'like', '%'.$searchValue.'%');
                      })
                      ->orWhere('description', 'like', '%'.$searchValue.'%');
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
            // Default sorting by incident_date desc
            $result = $result->pushCriteria(new OrderByFieldCriteria('incident_date', 'desc'));
        }
        
        // Apply pagination
        $result = $result->pushCriteria(new SkipTakeCriteria($skip, $pageSize));

        // Get filtered data with relationships
        $data = $result->with(['child', 'childcareCenter', 'room', 'reportedBy'])->all();

        // Transform data for DataTables
        $transformedData = [];
        foreach ($data as $item) {
            $child = $item->child;

            // Check if incident needs attention using model method
            $needsAttention = $item->needsAttention();

            $transformedData[] = [
                'id' => $item->id,
                'code' => $item->code,
                'child_id' => $child ? $child->id : null,
                'child_name' => $child ? $child->full_name : '-',
                'child_initials' => $child ? $child->initials : '??',
                'child_age_readable' => $child ? $child->age_readable : '-',
                'childcare_center_name' => $item->childcareCenter ? $item->childcareCenter->name : '-',
                'room_name' => $item->room ? $item->room->name : '-',
                'type' => $item->type ? $item->type->label() : '-',
                'type_value' => $item->type ? $item->type->value : null,
                'severity' => $item->severity_level ? $item->severity_level->label() : '-',
                'severity_value' => $item->severity_level ? $item->severity_level->value : null,
                'severity_color' => $item->severity_level ? $item->severity_level->color() : null,
                'status' => $item->status ? $item->status->label() : '-',
                'status_value' => $item->status ? $item->status->value : null,
                'incident_date' => $item->incident_date ? $item->incident_date->format('d/m/Y') : '-',
                'reported_at' => $item->reported_at ? $item->reported_at->format('d/m/Y H:i') : '-',
                'reported_by' => $item->reportedBy ? $item->reportedBy->name : '-',
                'escalated_to' => $item->escalated_to ?: '-',
                'needs_attention' => $needsAttention,
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

