<?php

namespace App\Containers\Monitoring\Child\Actions;

use App\Containers\AppSection\ActivityLog\Constants\LogConstants;
use App\Containers\AppSection\ActivityLog\Events\ActivityLogEvent;
use App\Containers\AppSection\File\Tasks\CreateFileTask;
use App\Containers\AppSection\User\Enums\Gender;
use App\Containers\Monitoring\Child\Enums\GuardianType;
use App\Containers\Monitoring\Child\Models\Child;
use App\Containers\Monitoring\Child\Tasks\CreateChildFamilyMemberTask;
use App\Containers\Monitoring\Child\Tasks\CreateChildMedicalRecordTask;
use App\Containers\Monitoring\Child\Tasks\CreateChildSocialRecordTask;
use App\Containers\Monitoring\Child\Tasks\CreateChildTask;
use App\Containers\Monitoring\ChildEnrollment\Enums\EnrollmentStatus;
use App\Containers\Monitoring\ChildEnrollment\Tasks\CreateChildEnrollmentTask;
use App\Containers\Monitoring\ChildEnrollment\Models\ChildEnrollment;
use App\Containers\Monitoring\Child\UI\API\Requests\CreateChildRequest;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\DB;

final class CreateChildAction extends ParentAction
{
    public function __construct(
        private readonly CreateChildTask $createChildTask,
        private readonly CreateChildMedicalRecordTask $createChildMedicalRecordTask,
        private readonly CreateChildSocialRecordTask $createChildSocialRecordTask,
        private readonly CreateChildFamilyMemberTask $createChildFamilyMemberTask,
        private readonly CreateChildEnrollmentTask $createChildEnrollmentTask,
        private readonly CreateFileTask $createFileTask,
    ) {
    }

    public function run(CreateChildRequest $request): Child
    {
        // dd($request->all());
        $data = $request->sanitize([
            // Basic child information
            'first_name',
            'paternal_last_name',
            'maternal_last_name',
            'birth_date',
            'gender',
            'state',
            'city',
            'address',

            // Enrollment information
            'childcare_center_id',
            'room_id',
            'enrollment_date',

            // Medical information
            'has_insurance',
            'insurance_details',
            'weight',
            'height',

            'has_allergies',
            'allergies_details',
            'has_medical_treatment',
            'medical_treatment_details',
            'has_psychological_treatment',
            'psychological_treatment_details',
            'has_deficit',
            'deficit_auditory',
            'deficit_visual',
            'deficit_tactile',
            'deficit_motor',
            'has_illness',
            'illness_details',
            'other_observations',

            // Social information
            'guardian_type',
            'housing_type',
            'housing_tenure',
            'housing_wall_material',
            'housing_floor_material',
            'housing_finish',
            'housing_bedrooms',
            'housing_rooms',
            'housing_utilities',
            'transport_type',
            'travel_time',

            // Family members (will be processed separately)
            // 'family_members',

            

        ]);

        return DB::transaction(function () use ($data, $request) {

            // 1. Create the child
            $generalData = [
                'first_name' => $data['first_name'],
                'paternal_last_name' => $data['paternal_last_name'],
                'maternal_last_name' => $data['maternal_last_name'],
                'gender' => $data['gender'] ?? Gender::UNSPECIFIED,
                'birth_date' => $data['birth_date'],
                'country' => $data['country'] ?? 'BO',
                'state' => $data['state'] ?? null,
                'city' => $data['city'] ?? null,
                'address' => $data['address'] ?? null,
                'language' => $data['language'] ?? null,
            ];
            $child = $this->createChildTask->run($generalData);

            // 2. Create medical record
            $medicalRecordData = [
                'child_id' => $child->id,
                'has_insurance' => $data['has_insurance'] ?? false,
                'insurance_details' => $data['insurance_details'] ?? null,
                'weight' => $data['weight'] ?? null,
                'height' => $data['height'] ?? null,
                'has_allergies' => $data['has_allergies'] ?? false,
                'allergies_details' => $data['allergies_details'] ?? null,
                'has_medical_treatment' => $data['has_medical_treatment'] ?? false,
                'medical_treatment_details' => $data['medical_treatment_details'] ?? null,
                'has_psychological_treatment' => $data['has_psychological_treatment'] ?? false,
                'psychological_treatment_details' => $data['psychological_treatment_details'] ?? null,
                'has_deficit' => $data['has_deficit'] ?? false,
                'deficit_auditory' => $data['deficit_auditory'] ?? null,
                'deficit_visual' => $data['deficit_visual'] ?? null,
                'deficit_tactile' => $data['deficit_tactile'] ?? null,
                'deficit_motor' => $data['deficit_motor'] ?? null,
                'has_illness' => $data['has_illness'] ?? false,
                'illness_details' => $data['illness_details'] ?? null,
                'other_observations' => $data['other_observations'] ?? null,
            ];
            $this->createChildMedicalRecordTask->run($medicalRecordData);

            // 3. Create social record (basic structure, will be updated with family members)
            $socialRecordData = [
                'child_id' => $child->id,
                'guardian_type' => $data['guardian_type'] ?? null,
                'housing_type' => $data['housing_type'] ?? null,
                'housing_tenure' => $data['housing_tenure'] ?? null,
                'housing_wall_material' => $data['housing_wall_material'] ?? null,
                'housing_floor_material' => $data['housing_floor_material'] ?? null,
                'housing_finish' => $data['housing_finish'] ?? null,
                'housing_bedrooms' => $data['housing_bedrooms'] ?? null,
                'housing_rooms' => $data['housing_rooms'] ?? null,
                'housing_utilities' => $data['housing_utilities'] ?? null,
                'transport_type' => $data['transport_type'] ?? null,
                'travel_time' => $data['travel_time'] ?? null,
            ];
            $socialRecord = $this->createChildSocialRecordTask->run($socialRecordData);

            // 4. Create family members if provided
            if ($request->has('family_members') && is_array($request->family_members)) {
                foreach ($request->family_members as $familyMemberData) {
                    // Mapear campos del frontend a los campos del modelo
                    $mappedData = [
                        'child_social_record_id' => $socialRecord->id,
                        'first_name' => $familyMemberData['first_name'] ?? null,
                        'last_name' => $familyMemberData['last_name'] ?? null,
                        'birth_date' => $familyMemberData['birth_date'] ?? null,
                        'gender' => $familyMemberData['gender'] ?? null,
                        'kinship' => $familyMemberData['relationship'] ?? $familyMemberData['kinship'] ?? null, // Mapear 'relationship' a 'kinship'
                        'education_level' => $familyMemberData['education_level'] ?? null,
                        'profession' => $familyMemberData['profession'] ?? null,
                        'marital_status' => $familyMemberData['marital_status'] ?? null,
                        'phone' => $familyMemberData['phone'] ?? null,
                        'has_income' => $familyMemberData['has_income'] ?? false,
                        'workplace' => $familyMemberData['workplace'] ?? null,
                        'income_type' => $familyMemberData['income_type'] ?? null,
                        'total_income' => $familyMemberData['income_total'] ?? $familyMemberData['total_income'] ?? null, // Mapear 'income_total' a 'total_income'
                    ];
                    $this->createChildFamilyMemberTask->run($mappedData);
                }
            }

            // 5. Create enrollment
            $enrollmentData = [
                'child_id' => $child->id,
                'childcare_center_id' => $data['childcare_center_id'],
                'room_id' => $data['room_id'],
                'enrollment_date' => $data['enrollment_date'],
                'status' => EnrollmentStatus::ACTIVE,
            ];
            $enrollment = $this->createChildEnrollmentTask->run($enrollmentData);

            // 6. Handle file uploads and associate them with the enrollment
            $this->handleFileUploads($request, $enrollment);

            // 7. Create activity log
            ActivityLogEvent::dispatch(LogConstants::REGISTER_CHILD, $request->server(), $child, $generalData);

            return $child->load(['medicalRecord', 'socialRecord.familyMembers', 'enrollments']);
        });
    }

    /**
     * Handle file uploads and associate them with the enrollment.
     */
    private function handleFileUploads(CreateChildRequest $request, ChildEnrollment $enrollment): void
    {
        $fileMappings = [
            'file_admission_request' => 'file_admission_request',
            'file_commitment' => 'file_commitment',
            'file_birth_certificate' => 'file_birth_certificate',
            'file_vaccination_card' => 'file_vaccination_card',
            'file_parent_id' => 'file_parent_id',
            'file_work_certificate' => 'file_work_certificate',
            'file_utility_bill' => 'file_utility_bill',
            'file_home_sketch' => 'file_home_sketch',
            'file_pickup_authorization' => 'file_pickup_authorization',
        ];

        foreach ($fileMappings as $requestField => $enrollmentField) {
            if ($request->hasFile($requestField)) {
                $files = is_array($request->file($requestField))
                    ? $request->file($requestField)
                    : [$request->file($requestField)];

                // Solo tomar el primer archivo válido
                foreach ($files as $file) {
                    if ($file && $file->isValid()) {
                        $uploadedFile = $this->createFileTask->run(
                            $file,
                            $enrollment->child_id,
                            $request->user()->id ?? null
                        );

                        // Guardar solo el ID del primer archivo válido
                        $enrollment->{$enrollmentField} = $uploadedFile->id;
                        break; // Solo procesar el primer archivo válido
                    }
                }
            }
        }

        $enrollment->save();
    }
}
