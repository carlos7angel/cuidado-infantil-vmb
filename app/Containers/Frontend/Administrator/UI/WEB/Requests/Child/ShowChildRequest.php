<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Child;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\Monitoring\Child\Tasks\FindChildByIdTask;
use App\Ship\Parents\Requests\Request as ParentRequest;

final class ShowChildRequest extends ParentRequest
{
    protected array $decode = [];

    protected array $urlParameters = [
        'id',
    ];

    public function rules(): array
    {
        return [
            // 'id' => 'required|exists:children,id',
        ];
    }

    public function authorize(): bool
    {
        $childId = $this->route('id');
        if (!$childId) {
            return false;
        }

        $user = $this->user();
        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            try {
                $child = app(FindChildByIdTask::class)->run($childId);
                $hasEnrollment = $child->enrollments()->where('childcare_center_id', $user->childcare_center_id)->exists();
                if (!$hasEnrollment) {
                    return false;
                }
            } catch (\Exception $e) {
                return false;
            }
        }
        
        return true;
    }
}
