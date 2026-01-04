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

final class CreateChildRequest extends ParentRequest
{
    protected array $decode = [
        'childcare_center_id',
        'room_id'
    ];

    public function rules(): array
    {
        return [
            'childcare_center_id' => 'required|exists:childcare_centers,id',
            'room_id' => 'required|exists:rooms,id',
            'enrollment_date' => 'required|date',
            
            'first_name' => 'required|string|max:255',
            'paternal_last_name' => 'required|string|max:255',
            'maternal_last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => Rule::enum(Gender::class),
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',

            'has_insurance' => 'required|boolean',
            'insurance_details' => 'string|max:500|nullable',
            'weight' => 'required|numeric|min:0|max:1000',
            'height' => 'required|numeric|min:0|max:1000',
            'has_allergies' => 'boolean',
            'allergies_details' => 'string|max:500|nullable',
            'has_medical_treatment' => 'boolean',
            'medical_treatment_details' => 'string|max:500|nullable',
            'has_psychological_treatment' => 'boolean',
            'psychological_treatment_details' => 'string|max:500|nullable',
            'has_deficit' => 'boolean',
            'deficit_auditory' => Rule::enum(DeficitLevel::class),
            'deficit_visual' => Rule::enum(DeficitLevel::class),
            'deficit_tactile' => Rule::enum(DeficitLevel::class),
            'deficit_motor' => Rule::enum(DeficitLevel::class),
            'has_illness' => 'boolean',
            'illness_details' => 'string|max:500|nullable',
            'other_observations' => 'string|max:500|nullable',
            
            'guardian_type' => Rule::enum(GuardianType::class),
            'housing_type' => Rule::enum(HousingType::class),
            'housing_tenure' => Rule::enum(HousingTenure::class),
            'housing_wall_material' => 'string|max:255|nullable',
            'housing_floor_material' => 'string|max:255|nullable',
            'housing_finish' => 'string|max:255|nullable',
            'housing_bedrooms' => 'integer|min:0|max:10',
            'housing_rooms' => 'array|nullable',
            'housing_rooms.*' => 'string|max:255',
            'housing_utilities' => 'array|nullable',
            'housing_utilities.*' => 'string|max:255',
            'transport_type' => Rule::enum(TransportType::class),
            'travel_time' => Rule::enum(TravelTime::class),
 
            'family_members' => 'array|nullable',
            'family_members.*.first_name' => 'required|string|max:255',
            'family_members.*.last_name' => 'required|string|max:255',
            'family_members.*.birth_date' => 'required|date',
            'family_members.*.gender' => Rule::enum(Gender::class),
            'family_members.*.kinship' => Rule::enum(Kinship::class),
            'family_members.*.relationship' => Rule::enum(Kinship::class), // Alias para kinship
            'family_members.*.education_level' => 'string|max:255|nullable',
            'family_members.*.profession' => 'string|max:255|nullable',
            'family_members.*.marital_status' => Rule::enum(MaritalStatus::class), // nullable
            'family_members.*.phone' => 'string|max:255|nullable',
            'family_members.*.has_income' => 'boolean|nullable',
            'family_members.*.workplace' => 'string|max:255|nullable',
            'family_members.*.income_type' => Rule::enum(\App\Containers\Monitoring\Child\Enums\IncomeType::class),
            'family_members.*.total_income' => 'numeric|nullable',
            'family_members.*.income_total' => 'numeric|nullable', // Alias para total_income

            // Archivos: acepta tanto archivo simple como array
            // Flutter puede enviar como file_vaccination_card (simple) o file_vaccination_card[0] (array)
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
            // Campos básicos requeridos
            'childcare_center_id.required' => 'El centro de cuidado infantil es requerido.',
            'childcare_center_id.exists' => 'El centro de cuidado infantil seleccionado no existe.',
            'room_id.required' => 'La sala/grupo es requerida.',
            'room_id.exists' => 'La sala/grupo seleccionada no existe.',
            'enrollment_date.required' => 'La fecha de inscripción es requerida.',
            'enrollment_date.date' => 'La fecha de inscripción debe ser una fecha válida.',
            
            // Información personal del niño
            'first_name.required' => 'El nombre es requerido.',
            'paternal_last_name.required' => 'El apellido paterno es requerido.',
            'maternal_last_name.required' => 'El apellido materno es requerido.',
            'birth_date.required' => 'La fecha de nacimiento es requerida.',
            'birth_date.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'gender.enum' => 'El género seleccionado no es válido.',
            'state.required' => 'El departamento/estado es requerido.',
            'city.required' => 'La ciudad es requerida.',
            'address.required' => 'La dirección es requerida.',
            
            // Información médica
            'has_insurance.required' => 'Debe indicar si el niño tiene seguro médico.',
            'has_insurance.boolean' => 'El campo de seguro médico debe ser verdadero o falso.',
            'weight.required' => 'El peso es requerido.',
            'weight.numeric' => 'El peso debe ser un número.',
            'weight.min' => 'El peso debe ser mayor o igual a 0.',
            'weight.max' => 'El peso no puede ser mayor a 1000 kg.',
            'height.required' => 'La estatura es requerida.',
            'height.numeric' => 'La estatura debe ser un número.',
            'height.min' => 'La estatura debe ser mayor o igual a 0.',
            'height.max' => 'La estatura no puede ser mayor a 1000 cm.',
            
            // Información de vivienda
            'housing_bedrooms.required' => 'El número de dormitorios es requerido.',
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
        ];
    }
}
