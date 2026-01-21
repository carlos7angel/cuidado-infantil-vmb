<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Monitoring;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\Monitoring\Child\Tasks\FindChildByIdTask;
use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListVaccinationTrackingByChildRequest extends ParentRequest
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
        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            try {
                $child = app(FindChildByIdTask::class)->run($childId);
                if (!$child->relationLoaded('activeEnrollment')) {
                    $child->load('activeEnrollment');
                }
                if (!$child->activeEnrollment || $child->activeEnrollment->childcare_center_id != $user->childcare_center_id) {
                    return false;
                }
            } catch (\Exception $e) {
                return false;
            }
        }
        
        return true;
    }
}
