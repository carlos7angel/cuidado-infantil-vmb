<?php

namespace App\Containers\Monitoring\Child\Actions;

use App\Containers\AppSection\File\Tasks\CreateFileTask;
use App\Containers\AppSection\User\Enums\Gender;
use App\Containers\Monitoring\Child\Enums\GuardianType;
use App\Containers\Monitoring\Child\Models\Child;
use App\Containers\Monitoring\Child\Tasks\CreateChildFamilyMemberTask;
use App\Containers\Monitoring\Child\Tasks\FindChildByIdTask;
use App\Containers\Monitoring\Child\Tasks\UpdateChildMedicalRecordTask;
use App\Containers\Monitoring\Child\Tasks\UpdateChildSocialRecordTask;
use App\Containers\Monitoring\Child\Tasks\UpdateChildTask;
use App\Containers\Monitoring\Child\Tasks\ProcessChildAvatarTask;
use App\Containers\Monitoring\Child\UI\API\Requests\UpdateChildRequest;
use App\Containers\Monitoring\ChildEnrollment\Enums\EnrollmentStatus;
use App\Containers\Monitoring\ChildEnrollment\Models\ChildEnrollment;
use App\Containers\Monitoring\ChildEnrollment\Tasks\UpdateChildEnrollmentTask;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class UpdateChildAction extends ParentAction
{
    public function __construct(
        private readonly FindChildByIdTask $findChildByIdTask,
        private readonly UpdateChildTask $updateChildTask,
        private readonly UpdateChildMedicalRecordTask $updateChildMedicalRecordTask,
        private readonly UpdateChildSocialRecordTask $updateChildSocialRecordTask,
        private readonly CreateChildFamilyMemberTask $createChildFamilyMemberTask,
        private readonly UpdateChildEnrollmentTask $updateChildEnrollmentTask,
        private readonly CreateFileTask $createFileTask,
        private readonly ProcessChildAvatarTask $processChildAvatarTask,
    ) {
    }

    public function run(UpdateChildRequest $request): Child
    {
        $data = $request->sanitize([
            // Basic child information
            'first_name',
            'paternal_last_name',
            'maternal_last_name',
            'birth_date',
            'gender',
            'state',
            'city',
            'municipality',
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
            'outstanding_skills',
            'nutritional_problems',
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
            'incident_history',
            'pets',
        ]);

        return DB::transaction(function () use ($data, $request) {
            // 1. Find the child
            $child = $this->findChildByIdTask->run($request->id);

            // Update basic child information (solo campos que vienen en el request)
            $generalData = array_filter([
                'first_name' => $data['first_name'] ?? null,
                'paternal_last_name' => $data['paternal_last_name'] ?? null,
                'maternal_last_name' => $data['maternal_last_name'] ?? null,
                'gender' => $data['gender'] ?? null,
                'birth_date' => $data['birth_date'] ?? null,
                'state' => $data['state'] ?? null,
                'city' => $data['city'] ?? null,
                'municipality' => $data['municipality'] ?? null,
                'address' => $data['address'] ?? null,
            ], fn($value) => $value !== null);

            if (!empty($generalData)) {
                $child = $this->updateChildTask->run($generalData, $request->id);
            }

            $this->handleAvatarUpload($request, $child);

            // 2. Update medical record (solo campos que vienen en el request)
            $medicalRecordData = array_filter([
                'has_insurance' => $data['has_insurance'] ?? null,
                'insurance_details' => $data['insurance_details'] ?? null,
                'weight' => $data['weight'] ?? null,
                'height' => $data['height'] ?? null,
                'has_allergies' => $data['has_allergies'] ?? null,
                'allergies_details' => $data['allergies_details'] ?? null,
                'has_medical_treatment' => $data['has_medical_treatment'] ?? null,
                'medical_treatment_details' => $data['medical_treatment_details'] ?? null,
                'has_psychological_treatment' => $data['has_psychological_treatment'] ?? null,
                'psychological_treatment_details' => $data['psychological_treatment_details'] ?? null,
                'has_deficit' => $data['has_deficit'] ?? null,
                'deficit_auditory' => $data['deficit_auditory'] ?? null,
                'deficit_visual' => $data['deficit_visual'] ?? null,
                'deficit_tactile' => $data['deficit_tactile'] ?? null,
                'deficit_motor' => $data['deficit_motor'] ?? null,
                'has_illness' => $data['has_illness'] ?? null,
                'illness_details' => $data['illness_details'] ?? null,
                'other_observations' => $data['other_observations'] ?? null,
                'outstanding_skills' => $data['outstanding_skills'] ?? null,
                'nutritional_problems' => $data['nutritional_problems'] ?? null,
            ], fn($value) => $value !== null);

            if (!empty($medicalRecordData)) {
                $this->updateChildMedicalRecordTask->run($medicalRecordData, $child->id);
            }

            // 3. Update social record (solo campos que vienen en el request)
            $socialRecordData = array_filter([
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
                'incident_history' => $data['incident_history'] ?? null,
                'pets' => $data['pets'] ?? null,
            ], fn($value) => $value !== null);

            // Obtener o actualizar social record
            $socialRecord = $child->socialRecord;
            if (!empty($socialRecordData)) {
                $socialRecord = $this->updateChildSocialRecordTask->run($socialRecordData, $child->id);
            } elseif (!$socialRecord && $request->has('family_members')) {
                // Si no existe social record pero hay family members, crear uno básico
                $socialRecord = $this->updateChildSocialRecordTask->run([], $child->id);
            }

            // 4. Handle family members (si se envía el array, reemplazar completamente)
            if ($request->has('family_members') && is_array($request->family_members)) {
                // Si existe social record, eliminar miembros anteriores y crear los nuevos
                if ($socialRecord) {
                    $socialRecord->familyMembers()->delete();
                    
                    foreach ($request->family_members as $familyMemberData) {
                        // Mapear campos del frontend a los campos del modelo
                        $mappedData = [
                            'child_social_record_id' => $socialRecord->id,
                            'first_name' => $familyMemberData['first_name'] ?? null,
                            'last_name' => $familyMemberData['last_name'] ?? null,
                            'birth_date' => $familyMemberData['birth_date'] ?? null,
                            'gender' => $familyMemberData['gender'] ?? null,
                            'kinship' => $familyMemberData['relationship'] ?? $familyMemberData['kinship'] ?? null,
                            'education_level' => $familyMemberData['education_level'] ?? null,
                            'profession' => $familyMemberData['profession'] ?? null,
                            'marital_status' => $familyMemberData['marital_status'] ?? null,
                            'phone' => $familyMemberData['phone'] ?? null,
                            'has_income' => $familyMemberData['has_income'] ?? false,
                            'workplace' => $familyMemberData['workplace'] ?? null,
                            'income_type' => $familyMemberData['income_type'] ?? null,
                            'total_income' => $familyMemberData['income_total'] ?? $familyMemberData['total_income'] ?? null,
                        ];
                        $this->createChildFamilyMemberTask->run($mappedData);
                    }
                }
            }

            // 5. Update active enrollment (solo si se envía información de enrollment)
            // Obtener el enrollment activo directamente desde la base de datos
            $enrollment = ChildEnrollment::where('child_id', $child->id)
                ->where('status', EnrollmentStatus::ACTIVE->value)
                ->first();
            
            if (!$enrollment) {
                // Si no hay enrollment activo, usar el más reciente
                $enrollment = ChildEnrollment::where('child_id', $child->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
            }
            
            if ($enrollment) {
                $enrollmentData = array_filter([
                    'childcare_center_id' => $data['childcare_center_id'] ?? null,
                    'room_id' => $data['room_id'] ?? null,
                    'enrollment_date' => $data['enrollment_date'] ?? null,
                ], fn($value) => $value !== null);

                if (!empty($enrollmentData)) {
                    $enrollment = $this->updateChildEnrollmentTask->run($enrollmentData, $enrollment->id);
                }

                // 6. Handle file uploads (solo actualizar si se envía un nuevo archivo)
                $this->handleFileUploads($request, $enrollment);
            }

            // Recargar el child con todas las relaciones actualizadas, incluyendo el enrollment con los archivos
            $child->refresh();
            return $child->load(['medicalRecord', 'socialRecord.familyMembers', 'enrollments']);
        });
    }

    /**
     * Handle file uploads and associate them with the enrollment.
     * Solo actualiza archivos si se envía un nuevo archivo.
     */
    private function handleFileUploads(UpdateChildRequest $request, ?ChildEnrollment $enrollment): void
    {
        if (!$enrollment) {
            return;
        }

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

        $hasChanges = false;

        foreach ($fileMappings as $requestField => $enrollmentField) {
            // Verificar si hay un archivo en el request
            $file = $request->file($requestField);
            
            // Si no hay archivo directo, verificar si está en un array (formato Flutter)
            if (!$file && $request->has($requestField)) {
                $fieldValue = $request->input($requestField);
                if (is_array($fieldValue)) {
                    $file = $fieldValue[0] ?? null;
                }
            }
            
            // Si aún no hay archivo, verificar hasFile (último recurso)
            if (!$file && $request->hasFile($requestField)) {
                $file = $request->file($requestField);
            }

            // Manejar array de archivos
            if (is_array($file)) {
                $file = $file[0] ?? null;
            }

            // Solo procesar si el archivo existe y es válido
            if ($file && $file->isValid()) {
                try {
                    $uploadedFile = $this->createFileTask->run(
                        $file,
                        $enrollment->child_id,
                        $request->user()->id ?? null
                    );

                    // Actualizar solo el ID del archivo nuevo
                    $enrollment->{$enrollmentField} = $uploadedFile->id;
                    $hasChanges = true;
                } catch (\Exception $e) {
                    // Log error pero continuar con otros archivos
                    Log::error("Error uploading file {$requestField}: " . $e->getMessage());
                }
            }
        }

        // Guardar todos los cambios de una vez si hubo algún cambio
        if ($hasChanges) {
            $enrollment->save();
            // Recargar el enrollment para asegurar que los cambios se reflejen
            $enrollment->refresh();
        }
    }

    private function handleAvatarUpload(UpdateChildRequest $request, Child $child): void
    {
        $file = $request->file('avatar');

        if (!$file && $request->has('avatar')) {
            $value = $request->input('avatar');
            if (is_array($value)) {
                $file = $value[0] ?? null;
            }
        }

        if (!$file && $request->hasFile('avatar')) {
            $file = $request->file('avatar');
        }

        if (is_array($file)) {
            $file = $file[0] ?? null;
        }

        if ($file && $file->isValid()) {
            try {
                $path = $this->processChildAvatarTask->run($file, $child->avatar);
                $child->avatar = $path;
                $child->save();
            } catch (\Exception $e) {
                Log::error('Error processing child avatar: ' . $e->getMessage());
            }
        }
    }
}
