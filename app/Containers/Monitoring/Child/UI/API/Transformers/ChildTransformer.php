<?php

namespace App\Containers\Monitoring\Child\UI\API\Transformers;

use App\Containers\Monitoring\Child\Models\Child;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;
use Vinkla\Hashids\Facades\Hashids;

final class ChildTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [
        'medical_record',
        'social_record',
        'family_members',
        'active_enrollment',
        'enrollments',
        'vaccinations',
        'nutritional_assessments',
        'files',
    ];

    public function transform(Child $child): array
    {
        $avatarUrl = $child->avatar ? url($child->avatar) : null;

        return [
            'type' => $child->getResourceKey(),
            'id' => $child->getHashedKey(),

            // General information
            'general' => [
                'hola' => 's',
                'first_name' => $child->first_name,
                'paternal_last_name' => $child->paternal_last_name,
                'maternal_last_name' => $child->maternal_last_name,
                'full_name' => $child->full_name,
                'gender' => $child->gender,
                'birth_date' => $child->birth_date,
                'age' => $child->age,
                'avatar' => $avatarUrl,
                'language' => $child->language,
                'country' => $child->country,
                'state' => $child->state,
                'city' => $child->city,
                'address' => $child->address,
            ],

            // Timestamps
            'created_at' => $child->created_at,
            'updated_at' => $child->updated_at,
            'readable_created_at' => $child->created_at->diffForHumans(),
            'readable_updated_at' => $child->updated_at->diffForHumans(),
        ];
    }

    /**
     * Include medical record.
     */
    public function includeMedicalRecord(Child $child)
    {
        if (!$child->relationLoaded('medicalRecord') || !$child->medicalRecord) {
            return $this->null();
        }

        $medicalRecord = $child->medicalRecord;

        return $this->item($medicalRecord, function ($record) {
            return [
                'type' => 'medical_record',
                'id' => $record->getHashedKey(),
                'has_insurance' => $record->has_insurance,
                'insurance_details' => $record->insurance_details,
                'weight' => $record->weight,
                'height' => $record->height,
                'has_allergies' => $record->has_allergies,
                'allergies_details' => $record->allergies_details,
                'has_medical_treatment' => $record->has_medical_treatment,
                'medical_treatment_details' => $record->medical_treatment_details,
                'has_psychological_treatment' => $record->has_psychological_treatment,
                'psychological_treatment_details' => $record->psychological_treatment_details,
                'has_deficit' => $record->has_deficit,
                'deficit_auditory' => $record->deficit_auditory,
                'deficit_visual' => $record->deficit_visual,
                'deficit_tactile' => $record->deficit_tactile,
                'deficit_motor' => $record->deficit_motor,
                'has_illness' => $record->has_illness,
                'illness_details' => $record->illness_details,
                'medical_report_document' => $record->medical_report_document,
                'diagnosis_document' => $record->diagnosis_document,
                'other_observations' => $record->other_observations,
                'created_at' => $record->created_at,
                'updated_at' => $record->updated_at,
            ];
        });
    }

    /**
     * Include social record.
     */
    public function includeSocialRecord(Child $child)
    {
        if (!$child->relationLoaded('socialRecord') || !$child->socialRecord) {
            return $this->null();
        }

        $socialRecord = $child->socialRecord;

        return $this->item($socialRecord, function ($record) {
            return [
                'type' => 'social_record',
                'id' => $record->getHashedKey(),
                'guardian_type' => $record->guardian_type,

                // Expenses
                'expenses' => [
                    'food' => $record->expense_food,
                    'education' => $record->expense_education,
                    'housing' => $record->expense_housing,
                    'transport' => $record->expense_transport,
                    'clothing' => $record->expense_clothing,
                    'utilities' => $record->expense_utilities,
                    'health' => $record->expense_health,
                    'debts' => $record->expense_debts,
                    'debts_detail' => $record->expense_debts_detail,
                    'total' => $record->total_expenses,
                ],

                // Housing
                'housing' => [
                    'type' => $record->housing_type,
                    'tenure' => $record->housing_tenure,
                    'wall_material' => $record->housing_wall_material,
                    'floor_material' => $record->housing_floor_material,
                    'finish' => $record->housing_finish,
                    'bedrooms' => $record->housing_bedrooms,
                    'rooms' => $record->housing_rooms,
                    'utilities' => $record->housing_utilities,
                ],

                // Transport
                'transport' => [
                    'type' => $record->transport_type,
                    'travel_time' => $record->travel_time,
                ],

                // Documents
                'home_sketch' => $record->home_sketch,
                'work_sketch' => $record->work_sketch,

                // Assessment
                'family_history' => $record->family_history,
                'professional_assessment' => $record->professional_assessment,

                'created_at' => $record->created_at,
                'updated_at' => $record->updated_at,
            ];
        });
    }

    /**
     * Include family members.
     */
    public function includeFamilyMembers(Child $child)
    {
        if (!$child->relationLoaded('socialRecord') || !$child->socialRecord) {
            return $this->collection(collect(), function () {
                return [];
            });
        }

        $socialRecord = $child->socialRecord;

        return $this->collection($socialRecord->familyMembers, function ($member) {
            return [
                'type' => 'family_member',
                'id' => $member->getHashedKey(),
                'first_name' => $member->first_name,
                'last_name' => $member->last_name,
                'full_name' => $member->full_name,
                'birth_date' => $member->birth_date,
                'age' => $member->age,
                'gender' => $member->gender,
                'kinship' => $member->kinship,
                'education_level' => $member->education_level,
                'profession' => $member->profession,
                'marital_status' => $member->marital_status,
                'phone' => $member->phone,
                'has_income' => $member->has_income,
                'workplace' => $member->workplace,
                'income_type' => $member->income_type,
                'total_income' => $member->total_income,
                'created_at' => $member->created_at,
                'updated_at' => $member->updated_at,
            ];
        });
    }

    /**
     * Include active enrollment.
     */
    public function includeActiveEnrollment(Child $child)
    {
        if (!$child->relationLoaded('activeEnrollment') || !$child->activeEnrollment) {
            return $this->null();
        }

        $enrollment = $child->activeEnrollment;

        // Load file relations if not already loaded
        $fileRelations = [
            'admissionRequestFile' => 'file_admission_request',
            'commitmentFile' => 'file_commitment',
            'birthCertificateFile' => 'file_birth_certificate',
            'vaccinationCardFile' => 'file_vaccination_card',
            'parentIdFile' => 'file_parent_id',
            'workCertificateFile' => 'file_work_certificate',
            'utilityBillFile' => 'file_utility_bill',
            'homeSketchFile' => 'file_home_sketch',
            'residenceCertificateFile' => 'file_residence_certificate',
            'pickupAuthorizationFile' => 'file_pickup_authorization'
        ];

        foreach ($fileRelations as $relation => $field) {
            if (!$enrollment->relationLoaded($relation) && !empty($enrollment->{$field})) {
                $enrollment->load($relation);
            }
        }

        return $this->item($enrollment, function ($enrollment) {
            return [
                'type' => 'enrollment',
                'id' => $enrollment->getHashedKey(),
                'childcare_center_id' => $enrollment->childcare_center_id,
                'room_id' => Hashids::encode($enrollment->room_id),
                'enrollment_date' => $enrollment->enrollment_date,
                'withdrawal_date' => $enrollment->withdrawal_date,
                'status' => $enrollment->status,
                'observations' => $enrollment->observations,

                // Documents - now returning file objects instead of IDs
                'file_admission_request' => $this->includeFileObject($enrollment, 'admissionRequestFile'),
                'file_commitment' => $this->includeFileObject($enrollment, 'commitmentFile'),
                'file_birth_certificate' => $this->includeFileObject($enrollment, 'birthCertificateFile'),
                'file_vaccination_card' => $this->includeFileObject($enrollment, 'vaccinationCardFile'),
                'file_parent_id' => $this->includeFileObject($enrollment, 'parentIdFile'),
                'file_work_certificate' => $this->includeFileObject($enrollment, 'workCertificateFile'),
                'file_utility_bill' => $this->includeFileObject($enrollment, 'utilityBillFile'),
                'file_home_sketch' => $this->includeFileObject($enrollment, 'homeSketchFile'),
                'file_residence_certificate' => $this->includeFileObject($enrollment, 'residenceCertificateFile'),
                'file_pickup_authorization' => $this->includeFileObject($enrollment, 'pickupAuthorizationFile'),

                'created_at' => $enrollment->created_at,
                'updated_at' => $enrollment->updated_at,
            ];
        });
    }

    /**
     * Include all enrollments.
     */
    public function includeEnrollments(Child $child)
    {
        if (!$child->relationLoaded('enrollments')) {
            return $this->collection(collect(), function () {
                return [];
            });
        }

        return $this->collection($child->enrollments, function ($enrollment) {
            // Load file relations if not already loaded
            $fileRelations = [
                'admissionRequestFile' => 'file_admission_request',
                'commitmentFile' => 'file_commitment',
                'birthCertificateFile' => 'file_birth_certificate',
                'vaccinationCardFile' => 'file_vaccination_card',
                'parentIdFile' => 'file_parent_id',
                'workCertificateFile' => 'file_work_certificate',
                'utilityBillFile' => 'file_utility_bill',
                'homeSketchFile' => 'file_home_sketch',
                'residenceCertificateFile' => 'file_residence_certificate',
                'pickupAuthorizationFile' => 'file_pickup_authorization'
            ];

            $relationsToLoad = [];
            foreach ($fileRelations as $relation => $field) {
                if (!$enrollment->relationLoaded($relation) && !empty($enrollment->{$field})) {
                    $relationsToLoad[] = $relation;
                }
            }

            if (!empty($relationsToLoad)) {
                $enrollment->load($relationsToLoad);
            }

            return [
                'type' => 'enrollment',
                'id' => $enrollment->getHashedKey(),
                'childcare_center_id' => $enrollment->childcare_center_id,
                'room_id' => Hashids::encode($enrollment->room_id),
                'room_name' => $enrollment->room?->name,
                'enrollment_date' => $enrollment->enrollment_date,
                'withdrawal_date' => $enrollment->withdrawal_date,
                'status' => $enrollment->status,
                'observations' => $enrollment->observations,

                // Documents - now returning file objects instead of IDs
                'file_admission_request' => $this->includeFileObject($enrollment, 'admissionRequestFile'),
                'file_commitment' => $this->includeFileObject($enrollment, 'commitmentFile'),
                'file_birth_certificate' => $this->includeFileObject($enrollment, 'birthCertificateFile'),
                'file_vaccination_card' => $this->includeFileObject($enrollment, 'vaccinationCardFile'),
                'file_parent_id' => $this->includeFileObject($enrollment, 'parentIdFile'),
                'file_work_certificate' => $this->includeFileObject($enrollment, 'workCertificateFile'),
                'file_utility_bill' => $this->includeFileObject($enrollment, 'utilityBillFile'),
                'file_home_sketch' => $this->includeFileObject($enrollment, 'homeSketchFile'),
                'file_residence_certificate' => $this->includeFileObject($enrollment, 'residenceCertificateFile'),
                'file_pickup_authorization' => $this->includeFileObject($enrollment, 'pickupAuthorizationFile'),

                'created_at' => $enrollment->created_at,
                'updated_at' => $enrollment->updated_at,
            ];
        });
    }

    /**
     * Include vaccinations.
     */
    public function includeVaccinations(Child $child)
    {
        if (!$child->relationLoaded('vaccinations')) {
            return $this->collection(collect(), function () {
                return [];
            });
        }

        return $this->collection($child->vaccinations, function ($vaccination) {
            return [
                'type' => 'vaccination',
                'id' => $vaccination->getHashedKey(),
                'vaccine_dose_id' => $vaccination->vaccine_dose_id,
                'vaccination_date' => $vaccination->vaccination_date,
                'batch_number' => $vaccination->batch_number,
                'administered_by' => $vaccination->administered_by,
                'observations' => $vaccination->observations,
                'created_at' => $vaccination->created_at,
                'updated_at' => $vaccination->updated_at,
            ];
        });
    }

    /**
     * Include nutritional assessments.
     */
    public function includeNutritionalAssessments(Child $child)
    {
        if (!$child->relationLoaded('nutritionalAssessments')) {
            return $this->collection(collect(), function () {
                return [];
            });
        }

        return $this->collection($child->nutritionalAssessments, function ($assessment) {
            return [
                'type' => 'nutritional_assessment',
                'id' => $assessment->getHashedKey(),
                'assessment_date' => $assessment->assessment_date,
                'weight' => $assessment->weight,
                'height' => $assessment->height,
                'z_weight_height' => $assessment->z_weight_height,
                'z_weight_age' => $assessment->z_weight_age,
                'z_height_age' => $assessment->z_height_age,
                'classification' => $assessment->classification,
                'requires_attention' => $assessment->requires_attention,
                'next_assessment_date' => $assessment->next_assessment_date,
                'recommendations' => $assessment->recommendations,
                'assessed_by' => $assessment->assessed_by,
                'created_at' => $assessment->created_at,
                'updated_at' => $assessment->updated_at,
            ];
        });
    }

    /**
     * Include files.
     */
    public function includeFiles(Child $child)
    {
        if (!$child->relationLoaded('files')) {
            return $this->collection(collect(), function () {
                return [];
            });
        }

        return $this->collection($child->files, function ($file) {
            return [
                'type' => 'file',
                'id' => $file->getHashedKey(),
                'unique_code' => $file->unique_code,
                'file_hash' => $file->file_hash,
                'name' => $file->name,
                'original_name' => $file->original_name,
                'description' => $file->description,
                'mime_type' => $file->mime_type,
                'size' => $file->size,
                'human_readable_size' => $file->human_readable_size,
                'url' => $file->url,
                'path' => $file->path,
                'options' => $file->options,
                'locale_upload' => $file->locale_upload,
                'status' => $file->status,
                'extension' => $file->extension,
                'is_image' => $file->is_image,
                'is_document' => $file->is_document,
                'type_category' => $file->getTypeCategory(),
                'created_at' => $file->created_at,
                'updated_at' => $file->updated_at,
            ];
        });
    }

    /**
     * Helper method to include file object or null.
     */
    private function includeFileObject($model, string $relationName)
    {
        if (!$model->relationLoaded($relationName) || !$model->{$relationName}) {
            return null;
        }

        $file = $model->{$relationName};

        return [
            'type' => 'file',
            'id' => $file->getHashedKey(),
            'unique_code' => $file->unique_code,
            'file_hash' => $file->file_hash,
            'name' => $file->name,
            'original_name' => $file->original_name,
            'description' => $file->description,
            'mime_type' => $file->mime_type,
            'size' => $file->size,
            'human_readable_size' => $file->human_readable_size,
            'url' => $file->url,
            'path' => $file->path,
            'options' => $file->options,
            'locale_upload' => $file->locale_upload,
            'status' => $file->status,
            'extension' => $file->extension,
            'is_image' => $file->is_image,
            'is_document' => $file->is_document,
            'type_category' => $file->getTypeCategory(),
            'created_at' => $file->created_at,
            'updated_at' => $file->updated_at,
        ];
    }
}
