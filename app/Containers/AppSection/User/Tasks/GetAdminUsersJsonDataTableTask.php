<?php

namespace App\Containers\AppSection\User\Tasks;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Http\Request;

final class GetAdminUsersJsonDataTableTask extends ParentTask
{
    public function run(Request $request): array
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

        // Get query for admin users
        $query = User::with(['roles'])
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', [
                    Role::MUNICIPAL_ADMIN->value,
                    Role::CHILDCARE_ADMIN->value,
                ]);
            });

        // Get total count before any filtering (recordsTotal)
        $recordsTotal = $query->count();

        // Apply global search filter
        if (!empty($searchValue)) {
            $query = $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', '%'.$searchValue.'%')
                  ->orWhere('email', 'like', '%'.$searchValue.'%')
                  ->orWhereHas('roles', function ($roleQuery) use ($searchValue) {
                      $roleQuery->where('display_name', 'like', '%'.$searchValue.'%');
                  });
            });
        }

        // Get filtered count before pagination (recordsFiltered)
        $recordsFiltered = $query->count();

        // Apply sorting
        if ($sortColumn != null && $sortColumn != "" && $sortColumnDir != null && $sortColumnDir != "") {
            if ($sortColumn === 'created_at') {
                $query = $query->orderBy('created_at', $sortColumnDir);
            } elseif ($sortColumn === 'role_display') {
                // For role sorting, we need to join with roles table
                // For simplicity, we'll sort by user name as default
                $query = $query->orderBy('name', $sortColumnDir);
            } else {
                $query = $query->orderBy('name', $sortColumnDir);
            }
        } else {
            // Default sorting by created_at desc
            $query = $query->orderBy('created_at', 'desc');
        }

        // Apply pagination
        $data = $query->skip($skip)->take($pageSize)->get();

        // Transform data for DataTables
        $transformedData = [];
        foreach ($data as $user) {
            $role = $user->roles->first();
            $transformedData[] = [
                'id' => $user->id,
                'user_info' => [
                    'id' => $user->id, // Add ID for JS access
                    'avatar' => asset('themes/common/media/images/blank-user.jpg'),
                    'name' => $user->name ?? 'N/A',
                    'email' => $user->email ?? 'N/A',
                ],
                'role_display' => $role ? $role->display_name : 'N/A',
                'status' => $user->active ? 'Activo' : 'Inactivo',
                'created_at' => $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-',
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
