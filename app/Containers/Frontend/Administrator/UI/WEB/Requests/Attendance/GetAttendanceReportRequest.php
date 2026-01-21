<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Attendance;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Ship\Parents\Requests\Request as ParentRequest;

final class GetAttendanceReportRequest extends ParentRequest
{
    public function rules(): array
    {
        return [
            'childcare_center_id' => 'nullable|exists:childcare_centers,id',
            'start_date' => 'nullable|date_format:d/m/Y',
            'end_date' => 'nullable|date_format:d/m/Y|after_or_equal:start_date',
            'report_type' => 'nullable|in:complete,simplified',
        ];
    }

    public function authorize(): bool
    {
        $user = $this->user();
        
        if (!$user->hasAnyRole([Role::SUPER_ADMIN, Role::MUNICIPAL_ADMIN, Role::CHILDCARE_ADMIN])) {
            return false;
        }

        // If user is childcare_admin, they can only request report for their center
        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            $requestedCenterId = $this->input('childcare_center_id');
            // If center ID is provided, it must match user's center
            if ($requestedCenterId && $requestedCenterId != $user->childcare_center_id) {
                return false;
            }
        }

        return true;
    }

    protected function prepareForValidation()
    {
        $user = $this->user();
        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            $this->merge([
                'childcare_center_id' => $user->childcare_center_id,
            ]);
        }
    }
}
