<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Monitoring;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\Monitoring\ChildDevelopment\Tasks\FindChildDevelopmentByIdTask;
use App\Ship\Parents\Requests\Request as ParentRequest;

final class GetDevelopmentEvaluationIndicatorsRequest extends ParentRequest
{
    protected array $decode = [
        'development_evaluation_id',
    ];

    protected array $urlParameters = [
        'development_evaluation_id',
    ];

    public function rules(): array
    {
        return [
            // 'development_evaluation_id' => 'required|exists:child_development_evaluations,id',
        ];
    }

    public function authorize(): bool
    {
        $evaluationId = $this->route('development_evaluation_id');
        if (!$evaluationId) {
            return false;
        }

        $user = $this->user();
        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            try {
                $evaluation = app(FindChildDevelopmentByIdTask::class)->run($evaluationId);
                $child = $evaluation->child;
                
                if (!$child) {
                    return false;
                }
                
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
