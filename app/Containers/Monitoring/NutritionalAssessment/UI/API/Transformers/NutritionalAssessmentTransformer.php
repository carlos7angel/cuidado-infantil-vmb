<?php

namespace App\Containers\Monitoring\NutritionalAssessment\UI\API\Transformers;

use App\Containers\Monitoring\NutritionalAssessment\Models\NutritionalAssessment;
use Vinkla\Hashids\Facades\Hashids;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class NutritionalAssessmentTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(NutritionalAssessment $nutritionalAssessment): array
    {
        return [
            'type' => $nutritionalAssessment->getResourceKey(),
            'id' => $nutritionalAssessment->getHashedKey(),
            'child_id' => $nutritionalAssessment->child_id ? Hashids::encode($nutritionalAssessment->child_id) : null,
            'assessment_date' => $nutritionalAssessment->assessment_date->format('Y-m-d'),
            'age_in_months' => $nutritionalAssessment->age_in_months,
            'age_readable' => $nutritionalAssessment->age_readable,
            
            // Mediciones antropométricas
            'weight' => $nutritionalAssessment->weight,
            'height' => $nutritionalAssessment->height,
            'head_circumference' => $nutritionalAssessment->head_circumference,
            'arm_circumference' => $nutritionalAssessment->arm_circumference,
            'bmi' => $nutritionalAssessment->bmi,
            
            // Z-scores calculados
            'z_weight_age' => $nutritionalAssessment->z_weight_age,
            'z_height_age' => $nutritionalAssessment->z_height_age,
            'z_weight_height' => $nutritionalAssessment->z_weight_height,
            'z_bmi_age' => $nutritionalAssessment->z_bmi_age,
            
            // Clasificaciones nutricionales
            'status_weight_age' => $nutritionalAssessment->status_weight_age?->value,
            'status_weight_age_label' => $nutritionalAssessment->status_weight_age?->labelForWeightForAge(),
            'status_weight_age_interpretation' => $nutritionalAssessment->status_weight_age?->interpretationForWeightForAge(),
            'status_height_age' => $nutritionalAssessment->status_height_age?->value,
            'status_height_age_label' => $nutritionalAssessment->status_height_age?->labelForHeightForAge(),
            'status_height_age_interpretation' => $nutritionalAssessment->status_height_age?->interpretationForHeightForAge(),
            'status_weight_height' => $nutritionalAssessment->status_weight_height?->value,
            'status_weight_height_label' => $nutritionalAssessment->status_weight_height?->labelForWeightForHeight(),
            'status_weight_height_interpretation' => $nutritionalAssessment->status_weight_height?->interpretationForWeightForHeight(),
            'status_bmi_age' => $nutritionalAssessment->status_bmi_age?->value,
            'status_bmi_age_label' => $nutritionalAssessment->status_bmi_age?->labelForBmiForAge(),
            'status_bmi_age_interpretation' => $nutritionalAssessment->status_bmi_age?->interpretationForBmiForAge(),
            
            // Indicadores
            'requires_attention' => $nutritionalAssessment->requiresAttention(),
            'critical_status' => $nutritionalAssessment->getMostCriticalStatus()?->value,
            'critical_status_label' => $nutritionalAssessment->getMostCriticalStatusLabel(),
            
            // Observaciones
            'observations' => $nutritionalAssessment->observations,
            'recommendations' => $nutritionalAssessment->recommendations,
            'next_assessment_date' => $nutritionalAssessment->next_assessment_date?->format('Y-m-d'),
            
            // Metadatos
            'created_at' => $nutritionalAssessment->created_at,
            'updated_at' => $nutritionalAssessment->updated_at,
            'readable_created_at' => $nutritionalAssessment->created_at->diffForHumans(),
            'readable_updated_at' => $nutritionalAssessment->updated_at->diffForHumans(),
        ];
    }
}
