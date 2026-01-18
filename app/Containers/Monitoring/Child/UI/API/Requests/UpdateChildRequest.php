<?php

namespace App\Containers\Monitoring\Child\UI\API\Requests;

use App\Containers\AppSection\User\Enums\Gender;
use App\Containers\Monitoring\Child\Enums\DeficitLevel;
use App\Containers\Monitoring\Child\Enums\GuardianType;
use App\Containers\Monitoring\Child\Enums\HousingTenure;
use App\Containers\Monitoring\Child\Enums\HousingType;
use App\Containers\Monitoring\Child\Enums\Kinship;
use App\Containers\Monitoring\Child\Enums\MaritalStatus;
use App\Containers\Monitoring\Child\Enums\TransportType;
use App\Containers\Monitoring\Child\Enums\TravelTime;
use App\Ship\Parents\Requests\Request as ParentRequest;
use Illuminate\Validation\Rule;

final class UpdateChildRequest extends ParentRequest
{
    protected array $decode = [
        'id',
        'childcare_center_id',
        'room_id'
    ];

    public function rules(): array
    {
        return [
            // Enrollment information (opcional en actualización)
            'childcare_center_id' => 'nullable|exists:childcare_centers,id',
            'room_id' => 'nullable|exists:rooms,id',
            'enrollment_date' => 'nullable|date',
            
            // Basic child information (opcional)
            'first_name' => 'nullable|string|max:255',
            'paternal_last_name' => 'nullable|string|max:255',
            'maternal_last_name' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => Rule::enum(Gender::class),
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',

            'avatar' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'avatar.*' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',

            // Medical information (opcional)
            'has_insurance' => 'nullable|boolean',
            'insurance_details' => 'string|max:500|nullable',
            'weight' => 'nullable|numeric|min:0|max:1000',
            'height' => 'nullable|numeric|min:0|max:1000',
            'has_allergies' => 'nullable|boolean',
            'allergies_details' => 'string|max:500|nullable',
            'has_medical_treatment' => 'nullable|boolean',
            'medical_treatment_details' => 'string|max:500|nullable',
            'has_psychological_treatment' => 'nullable|boolean',
            'psychological_treatment_details' => 'string|max:500|nullable',
            'has_deficit' => 'nullable|boolean',
            'deficit_auditory' => Rule::enum(DeficitLevel::class),
            'deficit_visual' => Rule::enum(DeficitLevel::class),
            'deficit_tactile' => Rule::enum(DeficitLevel::class),
            'deficit_motor' => Rule::enum(DeficitLevel::class),
            'has_illness' => 'nullable|boolean',
            'illness_details' => 'string|max:500|nullable',
            'other_observations' => 'string|max:500|nullable',
            
            // Social information (opcional)
            'guardian_type' => Rule::enum(GuardianType::class),
            'housing_type' => Rule::enum(HousingType::class),
            'housing_tenure' => Rule::enum(HousingTenure::class),
            'housing_wall_material' => 'string|max:255|nullable',
            'housing_floor_material' => 'string|max:255|nullable',
            'housing_finish' => 'string|max:255|nullable',
            'housing_bedrooms' => 'nullable|integer|min:0|max:10',
            'housing_rooms' => 'array|nullable',
            'housing_rooms.*' => 'string|max:255',
            'housing_utilities' => 'array|nullable',
            'housing_utilities.*' => 'string|max:255',
            'transport_type' => Rule::enum(TransportType::class),
            'travel_time' => Rule::enum(TravelTime::class),
 
            // Family members (opcional, puede actualizar la lista completa)
            'family_members' => 'array|nullable',
            'family_members.*.first_name' => 'required|string|max:255',
            'family_members.*.last_name' => 'required|string|max:255',
            'family_members.*.birth_date' => 'required|date',
            'family_members.*.gender' => Rule::enum(Gender::class),
            'family_members.*.kinship' => Rule::enum(Kinship::class),
            'family_members.*.relationship' => Rule::enum(Kinship::class), // Alias para kinship
            'family_members.*.education_level' => 'string|max:255|nullable',
            'family_members.*.profession' => 'string|max:255|nullable',
            'family_members.*.marital_status' => Rule::enum(MaritalStatus::class),
            'family_members.*.phone' => 'string|max:255|nullable',
            'family_members.*.has_income' => 'boolean|nullable',
            'family_members.*.workplace' => 'string|max:255|nullable',
            'family_members.*.income_type' => Rule::enum(\App\Containers\Monitoring\Child\Enums\IncomeType::class),
            'family_members.*.total_income' => 'numeric|nullable',
            'family_members.*.income_total' => 'numeric|nullable', // Alias para total_income

            // Archivos: acepta tanto archivo simple como array (opcional - solo actualiza si se envía)
            'file_admission_request' => 'nullable|file|mimes:pdf|max:5120',
            'file_admission_request.*' => 'nullable|file|mimes:pdf|max:5120',
            'file_commitment' => 'nullable|file|mimes:pdf|max:5120',
            'file_commitment.*' => 'nullable|file|mimes:pdf|max:5120',
            'file_birth_certificate' => 'nullable|file|mimes:pdf|max:5120',
            'file_birth_certificate.*' => 'nullable|file|mimes:pdf|max:5120',
            'file_vaccination_card' => 'nullable|file|mimes:pdf|max:5120',
            'file_vaccination_card.*' => 'nullable|file|mimes:pdf|max:5120',
            'file_parent_id' => 'nullable|file|mimes:pdf|max:5120',
            'file_parent_id.*' => 'nullable|file|mimes:pdf|max:5120',
            'file_work_certificate' => 'nullable|file|mimes:pdf|max:5120',
            'file_work_certificate.*' => 'nullable|file|mimes:pdf|max:5120',
            'file_utility_bill' => 'nullable|file|mimes:pdf|max:5120',
            'file_utility_bill.*' => 'nullable|file|mimes:pdf|max:5120',
            'file_home_sketch' => 'nullable|file|mimes:pdf|max:5120',
            'file_home_sketch.*' => 'nullable|file|mimes:pdf|max:5120',
            'file_pickup_authorization' => 'nullable|file|mimes:pdf|max:5120',
            'file_pickup_authorization.*' => 'nullable|file|mimes:pdf|max:5120',
        ];
    }

    /**
     * Get custom validation error messages.
     */
    public function messages(): array
    {
        return [
            // Campos básicos
            'childcare_center_id.exists' => 'El centro de cuidado infantil seleccionado no existe.',
            'room_id.exists' => 'La sala/grupo seleccionada no existe.',
            'enrollment_date.date' => 'La fecha de inscripción debe ser una fecha válida.',
            
            // Información personal del niño
            'birth_date.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'gender.enum' => 'El género seleccionado no es válido.',
            
            // Información médica
            'has_insurance.boolean' => 'El campo de seguro médico debe ser verdadero o falso.',
            'weight.numeric' => 'El peso debe ser un número válido.',
            'weight.min' => 'El peso debe ser mayor o igual a 0.',
            'weight.max' => 'El peso no puede ser mayor a 1000 kg.',
            'height.numeric' => 'La estatura debe ser un número válido.',
            'height.min' => 'La estatura debe ser mayor o igual a 0.',
            'height.max' => 'La estatura no puede ser mayor a 1000 cm.',
            
            // Información de vivienda
            'housing_bedrooms.integer' => 'El número de dormitorios debe ser un número entero.',
            'housing_bedrooms.min' => 'El número de dormitorios debe ser mayor o igual a 0.',
            'housing_bedrooms.max' => 'El número de dormitorios no puede ser mayor a 10.',
            'housing_rooms.array' => 'Las habitaciones deben ser una lista.',
            'housing_utilities.array' => 'Los servicios básicos deben ser una lista.',
            'guardian_type.enum' => 'El tipo de tutor seleccionado no es válido.',
            'housing_type.enum' => 'El tipo de vivienda seleccionado no es válido.',
            'housing_tenure.enum' => 'El tipo de tenencia de vivienda seleccionado no es válido.',
            'transport_type.enum' => 'El tipo de transporte seleccionado no es válido.',
            'travel_time.enum' => 'El tiempo de viaje seleccionado no es válido.',
            
            // Déficits
            'deficit_auditory.enum' => 'El nivel de déficit auditivo seleccionado no es válido.',
            'deficit_visual.enum' => 'El nivel de déficit visual seleccionado no es válido.',
            'deficit_tactile.enum' => 'El nivel de déficit táctil seleccionado no es válido.',
            'deficit_motor.enum' => 'El nivel de déficit motor seleccionado no es válido.',
            
            // Miembros de familia
            'family_members.array' => 'Los miembros de familia deben ser una lista.',
            'family_members.*.first_name.required' => 'El nombre del miembro de familia es requerido.',
            'family_members.*.last_name.required' => 'El apellido del miembro de familia es requerido.',
            'family_members.*.birth_date.required' => 'La fecha de nacimiento del miembro de familia es requerida.',
            'family_members.*.birth_date.date' => 'La fecha de nacimiento del miembro de familia debe ser una fecha válida.',
            'family_members.*.gender.enum' => 'El género del miembro de familia no es válido.',
            'family_members.*.kinship.enum' => 'El parentesco del miembro de familia no es válido.',
            'family_members.*.marital_status.enum' => 'El estado civil del miembro de familia no es válido.',
            
            // Archivos (acepta tanto archivo simple como array)
            'file_admission_request.file' => 'El archivo de solicitud de admisión debe ser un archivo válido.',
            'file_admission_request.mimes' => 'El archivo de solicitud de admisión debe ser un PDF.',
            'file_admission_request.max' => 'El archivo de solicitud de admisión no puede ser mayor a 5MB.',
            'file_admission_request.*.file' => 'El archivo de solicitud de admisión debe ser un archivo válido.',
            'file_admission_request.*.mimes' => 'El archivo de solicitud de admisión debe ser un PDF.',
            'file_admission_request.*.max' => 'El archivo de solicitud de admisión no puede ser mayor a 5MB.',
            'file_commitment.file' => 'El archivo de compromiso debe ser un archivo válido.',
            'file_commitment.mimes' => 'El archivo de compromiso debe ser un PDF.',
            'file_commitment.max' => 'El archivo de compromiso no puede ser mayor a 5MB.',
            'file_commitment.*.file' => 'El archivo de compromiso debe ser un archivo válido.',
            'file_commitment.*.mimes' => 'El archivo de compromiso debe ser un PDF.',
            'file_commitment.*.max' => 'El archivo de compromiso no puede ser mayor a 5MB.',
            'file_birth_certificate.file' => 'El certificado de nacimiento debe ser un archivo válido.',
            'file_birth_certificate.mimes' => 'El certificado de nacimiento debe ser un PDF.',
            'file_birth_certificate.max' => 'El certificado de nacimiento no puede ser mayor a 5MB.',
            'file_birth_certificate.*.file' => 'El certificado de nacimiento debe ser un archivo válido.',
            'file_birth_certificate.*.mimes' => 'El certificado de nacimiento debe ser un PDF.',
            'file_birth_certificate.*.max' => 'El certificado de nacimiento no puede ser mayor a 5MB.',
            'file_vaccination_card.file' => 'El carnet de vacunas debe ser un archivo válido.',
            'file_vaccination_card.mimes' => 'El carnet de vacunas debe ser un PDF.',
            'file_vaccination_card.max' => 'El carnet de vacunas no puede ser mayor a 5MB.',
            'file_vaccination_card.*.file' => 'El carnet de vacunas debe ser un archivo válido.',
            'file_vaccination_card.*.mimes' => 'El carnet de vacunas debe ser un PDF.',
            'file_vaccination_card.*.max' => 'El carnet de vacunas no puede ser mayor a 5MB.',
            'file_parent_id.file' => 'El documento de identidad del padre/madre debe ser un archivo válido.',
            'file_parent_id.mimes' => 'El documento de identidad del padre/madre debe ser un PDF.',
            'file_parent_id.max' => 'El documento de identidad del padre/madre no puede ser mayor a 5MB.',
            'file_parent_id.*.file' => 'El documento de identidad del padre/madre debe ser un archivo válido.',
            'file_parent_id.*.mimes' => 'El documento de identidad del padre/madre debe ser un PDF.',
            'file_parent_id.*.max' => 'El documento de identidad del padre/madre no puede ser mayor a 5MB.',
            'file_work_certificate.file' => 'El certificado laboral debe ser un archivo válido.',
            'file_work_certificate.mimes' => 'El certificado laboral debe ser un PDF.',
            'file_work_certificate.max' => 'El certificado laboral no puede ser mayor a 5MB.',
            'file_work_certificate.*.file' => 'El certificado laboral debe ser un archivo válido.',
            'file_work_certificate.*.mimes' => 'El certificado laboral debe ser un PDF.',
            'file_work_certificate.*.max' => 'El certificado laboral no puede ser mayor a 5MB.',
            'file_utility_bill.file' => 'El recibo de servicios debe ser un archivo válido.',
            'file_utility_bill.mimes' => 'El recibo de servicios debe ser un PDF.',
            'file_utility_bill.max' => 'El recibo de servicios no puede ser mayor a 5MB.',
            'file_utility_bill.*.file' => 'El recibo de servicios debe ser un archivo válido.',
            'file_utility_bill.*.mimes' => 'El recibo de servicios debe ser un PDF.',
            'file_utility_bill.*.max' => 'El recibo de servicios no puede ser mayor a 5MB.',
            'file_home_sketch.file' => 'El croquis de domicilio debe ser un archivo válido.',
            'file_home_sketch.mimes' => 'El croquis de domicilio debe ser un PDF.',
            'file_home_sketch.max' => 'El croquis de domicilio no puede ser mayor a 5MB.',
            'file_home_sketch.*.file' => 'El croquis de domicilio debe ser un archivo válido.',
            'file_home_sketch.*.mimes' => 'El croquis de domicilio debe ser un PDF.',
            'file_home_sketch.*.max' => 'El croquis de domicilio no puede ser mayor a 5MB.',
            'file_pickup_authorization.file' => 'La autorización de recojo debe ser un archivo válido.',
            'file_pickup_authorization.mimes' => 'La autorización de recojo debe ser un PDF.',
            'file_pickup_authorization.max' => 'La autorización de recojo no puede ser mayor a 5MB.',
            'file_pickup_authorization.*.file' => 'La autorización de recojo debe ser un archivo válido.',
            'file_pickup_authorization.*.mimes' => 'La autorización de recojo debe ser un PDF.',
            'file_pickup_authorization.*.max' => 'La autorización de recojo no puede ser mayor a 5MB.',
            'avatar.file' => 'La imagen de perfil debe ser un archivo válido.',
            'avatar.mimes' => 'La imagen de perfil debe ser un archivo de tipo JPEG, JPG, PNG o GIF.',
            'avatar.max' => 'La imagen de perfil no puede ser mayor a 5MB.',
            'avatar.*.file' => 'La imagen de perfil debe ser un archivo válido.',
            'avatar.*.mimes' => 'La imagen de perfil debe ser un archivo de tipo JPEG, JPG, PNG o GIF.',
            'avatar.*.max' => 'La imagen de perfil no puede ser mayor a 5MB.',
        ];
    }
}
