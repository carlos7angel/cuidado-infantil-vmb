<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Monitoring;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\Monitoring\NutritionalAssessment\Tasks\FindNutritionalAssessmentByIdTask;
use App\Ship\Parents\Requests\Request as ParentRequest;

final class DetailListNutritionAssessmentsByChildRequest extends ParentRequest
{
    protected array $decode = [
        'nutritional_assessment_id',
    ];

    protected array $urlParameters = [
        'nutritional_assessment_id',
    ];

    public function rules(): array
    {
        return [
            // 'nutritional_assessment_id' => 'required|exists:nutritional_assessments,id',
        ];
    }

    public function authorize(): bool
    {
        $assessmentId = $this->route('nutritional_assessment_id');
        if (!$assessmentId) {
            return false;
        }

        $user = $this->user();
        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            try {
                $assessment = app(FindNutritionalAssessmentByIdTask::class)->run($assessmentId);
                $child = $assessment->child;
                
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
