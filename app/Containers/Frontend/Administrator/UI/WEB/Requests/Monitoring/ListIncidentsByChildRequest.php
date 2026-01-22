<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Monitoring;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\Monitoring\Child\Tasks\FindChildByIdTask;
use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListIncidentsByChildRequest extends ParentRequest
{
    protected array $decode = [];

    protected array $urlParameters = [
        'child_id',
    ];

    public function rules(): array
    {
        return [
            // 'child_id' => 'required|exists:children,id',
        ];
    }

    public function authorize(): bool
    {
        $childId = $this->route('child_id');
        if (!$childId) {
            return false;
        }

        $user = $this->user();
        if ($user->hasRole(Role::SUPER_ADMIN) || $user->hasRole(Role::MUNICIPAL_ADMIN)) {
            return true;
        }

        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            $child = app(FindChildByIdTask::class)->run($childId);
            $activeEnrollment = $child->activeEnrollment;
            
            // Si el niño no está inscrito, no permitimos ver (o podríamos permitir si estuvo inscrito, 
            // pero por seguridad restringimos al centro actual)
            if (!$activeEnrollment) {
                // Si queremos permitir ver historial de niños que ya no están, tendríamos que buscar en enrollments históricos
                // Por simplicidad y seguridad actual: solo activos
                return false;
            }

            return $activeEnrollment->childcare_center_id === $user->childcare_center_id;
        }

        return false;
    }
}
