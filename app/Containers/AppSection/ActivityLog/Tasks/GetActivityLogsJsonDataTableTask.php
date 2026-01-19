<?php

namespace App\Containers\AppSection\ActivityLog\Tasks;

use App\Containers\AppSection\ActivityLog\Data\Repositories\ActivityLogRepository;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\ActivityLog\GetActivityLogsJsonDataTableRequest;
use App\Ship\Criteria\OrderByFieldCriteria;
use App\Ship\Criteria\SkipTakeCriteria;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Carbon\Carbon;

final class GetActivityLogsJsonDataTableTask extends ParentTask
{
    public function __construct(
        private readonly ActivityLogRepository $repository,
    ) {
    }

    public function run(GetActivityLogsJsonDataTableRequest $request): mixed
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
        $pageSize = $length != null ? (int) $length : 10;
        $skip = $start != null ? (int) $start : 0;

        $searchLogName = $requestData['columns'][1]['search']['value'] ?? '';
        $searchDescription = $requestData['columns'][2]['search']['value'] ?? '';

        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');

        $startDate = null;
        $endDate = null;

        if (!empty($startDateInput)) {
            try {
                $startDate = Carbon::createFromFormat('d/m/Y', $startDateInput)->startOfDay();
            } catch (\Exception $e) {
                $startDate = null;
            }
        }

        if (!empty($endDateInput)) {
            try {
                $endDate = Carbon::createFromFormat('d/m/Y', $endDateInput)->endOfDay();
            } catch (\Exception $e) {
                $endDate = null;
            }
        }

        $recordsTotal = $this->repository->count();

        $result = $this->repository->scopeQuery(function ($query) use (
            $searchValue,
            $searchLogName,
            $searchDescription,
            $startDate,
            $endDate,
        ) {
            if (!empty($searchLogName)) {
                $query = $query->where('log_name', 'like', '%'.$searchLogName.'%');
            }

            if (!empty($searchDescription)) {
                $query = $query->where('description', 'like', '%'.$searchDescription.'%');
            }

            if ($startDate && $endDate) {
                $query = $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            if (!empty($searchValue)) {
                $query = $query->where(function ($q) use ($searchValue) {
                    $q->where('log_name', 'like', '%'.$searchValue.'%')
                        ->orWhere('description', 'like', '%'.$searchValue.'%')
                        ->orWhere('ip_address', 'like', '%'.$searchValue.'%');

                    // Manually handle causer search assuming User model to avoid MorphTo whereHas issues
                    $userIds = \App\Containers\AppSection\User\Models\User::where('name', 'like', '%'.$searchValue.'%')
                        ->orWhere('email', 'like', '%'.$searchValue.'%')
                        ->pluck('id');

                    if ($userIds->isNotEmpty()) {
                        $q->orWhere(function ($subQ) use ($userIds) {
                            $subQ->whereIn('causer_id', $userIds)
                                ->where('causer_type', \App\Containers\AppSection\User\Models\User::class);
                        });
                    }
                });
            }

            return $query;
        });

        $recordsFiltered = (clone $result)->count();

        if ($sortColumn !== null && $sortColumn !== '' && $sortColumnDir !== null && $sortColumnDir !== '') {
            if (in_array($sortColumn, ['log_name', 'description', 'created_at', 'ip_address'], true)) {
                $result = $result->pushCriteria(new OrderByFieldCriteria($sortColumn, $sortColumnDir));
            } else {
                $result = $result->pushCriteria(new OrderByFieldCriteria('created_at', 'desc'));
            }
        } else {
            $result = $result->pushCriteria(new OrderByFieldCriteria('created_at', 'desc'));
        }

        $result = $result->pushCriteria(new SkipTakeCriteria($skip, $pageSize));

        $data = $result->with(['causer'])->all();

        $transformedData = [];

        foreach ($data as $item) {
            $causer = $item->causer;
            $properties = $item->properties ? $item->properties->toArray() : [];

            $transformedData[] = [
                'id' => $item->id,
                'log_name' => $item->log_name ?? '-',
                'description' => $item->description ?? '-',
                'user_name' => $causer ? ($causer->name ?? '-') : '-',
                'created_at' => $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-',
                'ip_address' => $item->ip_address ?? '-',
                'user_agent' => $item->user_agent ?? '-',
                'properties' => $properties,
            ];
        }

        return [
            'draw' => (int) $draw,
            'recordsFiltered' => $recordsFiltered,
            'recordsTotal' => $recordsTotal,
            'data' => $transformedData,
        ];
    }
}

