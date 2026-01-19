<?php

namespace App\Containers\Monitoring\Child\UI\API\Transformers;

use App\Containers\Monitoring\Child\Models\Child;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class ChildListTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [
        'medical_record',
        'social_record',
        'active_enrollment',
    ];

    public function transform(Child $child): array
    {
        $avatarUrl = $child->avatar ? url($child->avatar) : null;

        return [
            'type' => $child->getResourceKey(),
            'id' => $child->getHashedKey(),

            // Basic information for listings
            'first_name' => $child->first_name,
            'paternal_last_name' => $child->paternal_last_name,
            'maternal_last_name' => $child->maternal_last_name,
            'full_name' => $child->full_name,
            'gender' => $child->gender,
            'birth_date' => $child->birth_date,
            'age' => $child->age,
            'avatar' => $avatarUrl,

            // Location info
            'city' => $child->city,
            'state' => $child->state,

            // Status indicators
            'has_medical_record' => $child->relationLoaded('medicalRecord') && $child->medicalRecord !== null,
            'has_social_record' => $child->relationLoaded('socialRecord') && $child->socialRecord !== null,
            'has_active_enrollment' => $child->relationLoaded('activeEnrollment') && $child->activeEnrollment !== null,
            'files_count' => $child->relationLoaded('files') ? $child->files->count() : 0,

            // Timestamps
            'created_at' => $child->created_at,
            'updated_at' => $child->updated_at,
            'readable_created_at' => $child->created_at->diffForHumans(),
        ];
    }

    /**
     * Include basic medical record info.
     */
    public function includeMedicalRecord(Child $child)
    {
        if (!$child->relationLoaded('medicalRecord') || !$child->medicalRecord) {
            return $this->null();
        }

        return $this->item($child->medicalRecord, function ($record) {
            return [
                'has_insurance' => $record->has_insurance,
                'weight' => $record->weight,
                'height' => $record->height,
                'has_allergies' => $record->has_allergies,
                'has_medical_treatment' => $record->has_medical_treatment,
                'has_deficit' => $record->has_deficit,
                'has_illness' => $record->has_illness,
                'outstanding_skills' => $record->outstanding_skills,
                'nutritional_problems' => $record->nutritional_problems,
            ];
        });
    }

    /**
     * Include basic social record info.
     */
    public function includeSocialRecord(Child $child)
    {
        if (!$child->relationLoaded('socialRecord') || !$child->socialRecord) {
            return $this->null();
        }

        return $this->item($child->socialRecord, function ($record) {
            return [
                'guardian_type' => $record->guardian_type,
                'incident_history' => $record->incident_history,
                'pets' => $record->pets,
                'family_members_count' => $record->relationLoaded('familyMembers') ? $record->familyMembers->count() : 0,
            ];
        });
    }

    /**
     * Include active enrollment info.
     */
    public function includeActiveEnrollment(Child $child)
    {
        if (!$child->relationLoaded('activeEnrollment') || !$child->activeEnrollment) {
            return $this->null();
        }

        return $this->item($child->activeEnrollment, function ($enrollment) {
            return [
                'childcare_center_id' => $enrollment->childcare_center_id,
                'room_id' => $enrollment->room_id,
                'enrollment_date' => $enrollment->enrollment_date,
                'status' => $enrollment->status,
            ];
        });
    }
}
