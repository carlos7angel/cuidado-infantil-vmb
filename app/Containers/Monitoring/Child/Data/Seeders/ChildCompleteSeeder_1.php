<?php

namespace App\Containers\Monitoring\Child\Data\Seeders;

use App\Containers\AppSection\User\Enums\Gender;
use App\Containers\Monitoring\Child\Enums\DeficitLevel;
use App\Containers\Monitoring\Child\Enums\GuardianType;
use App\Containers\Monitoring\Child\Enums\HousingTenure;
use App\Containers\Monitoring\Child\Enums\HousingType;
use App\Containers\Monitoring\Child\Enums\IncomeType;
use App\Containers\Monitoring\Child\Enums\Kinship;
use App\Containers\Monitoring\Child\Enums\MaritalStatus;
use App\Containers\Monitoring\Child\Enums\TransportType;
use App\Containers\Monitoring\Child\Enums\TravelTime;
use App\Containers\Monitoring\Child\Models\Child;
use App\Ship\Parents\Seeders\Seeder as ParentSeeder;

final class ChildCompleteSeeder_1 extends ParentSeeder
{
    public function run(): void
    {
        // =====================================================================
        // CHILD
        // =====================================================================
        $child = Child::firstOrCreate(
            [
                'first_name' => 'María',
                'paternal_last_name' => 'Quispe',
                'maternal_last_name' => 'Mamani',
            ],
            [
                'gender' => Gender::FEMALE,
                'avatar' => null,
                'birth_date' => '2021-03-15',
                'language' => 'Español',
                'country' => 'Bolivia',
                'state' => 'La Paz',
                'city' => 'La Paz',
                'address' => 'Zona Villa Fátima, Calle 5 #123',
            ]
        );

        // =====================================================================
        // CHILD SOCIAL RECORD (1:1)
        // =====================================================================
        $socialRecord = $child->socialRecord()->firstOrCreate(
            ['child_id' => $child->id],
            [
                'guardian_type' => GuardianType::MOTHER,
                // Egresos
                'expense_food' => 800.00,
                'expense_education' => 150.00,
                'expense_housing' => 500.00,
                'expense_transport' => 200.00,
                'expense_clothing' => 100.00,
                'expense_utilities' => 150.00,
                'expense_health' => 100.00,
                'expense_debts' => 0.00,
                'expense_debts_detail' => null,
                // Habitabilidad
                'housing_type' => HousingType::HOUSE,
                'housing_tenure' => HousingTenure::RENTED,
                'housing_wall_material' => 'ladrillo',
                'housing_floor_material' => 'cemento',
                'housing_finish' => 'obra_gruesa',
                'housing_bedrooms' => 2,
                'housing_rooms' => ['dormitorio', 'sala', 'cocina', 'baño'],
                'housing_utilities' => ['agua_potable', 'energia_electrica', 'alcantarillado', 'gas'],
                // Transporte
                'transport_type' => TransportType::PUBLIC,
                'travel_time' => TravelTime::LESS_THAN_HALF_HOUR,
                // Croquis
                'home_sketch' => null,
                'work_sketch' => null,
                // Historia
                'family_history' => 'Familia monoparental. La madre trabaja como comerciante en el Mercado Rodriguez.',
                'professional_assessment' => 'Familia en situación económica estable con necesidades básicas cubiertas. Se recomienda seguimiento trimestral.',
            ]
        );

        // =====================================================================
        // CHILD FAMILY MEMBERS (1:N desde social_record)
        // =====================================================================
        if ($socialRecord->familyMembers()->count() === 0) {
            // Madre
            $socialRecord->familyMembers()->create([
                'first_name' => 'Rosa',
                'last_name' => 'Mamani Condori',
                'birth_date' => '1990-08-20',
                'gender' => Gender::FEMALE,
                'kinship' => Kinship::MOTHER,
                'education_level' => 'Secundaria completa',
                'profession' => 'Comerciante',
                'marital_status' => MaritalStatus::SINGLE,
                'phone' => '+591 71234567',
                'has_income' => true,
                'workplace' => 'Mercado Rodriguez',
                'income_type' => IncomeType::DAILY,
                'total_income' => 100.00,
            ]);

            // Abuela
            $socialRecord->familyMembers()->create([
                'first_name' => 'Juana',
                'last_name' => 'Condori Huanca',
                'birth_date' => '1960-05-10',
                'gender' => Gender::FEMALE,
                'kinship' => Kinship::GRANDMOTHER,
                'education_level' => 'Primaria',
                'profession' => 'Ama de casa',
                'marital_status' => MaritalStatus::WIDOWED,
                'phone' => '+591 71234568',
                'has_income' => false,
                'workplace' => null,
                'income_type' => null,
                'total_income' => null,
            ]);

            // Tío
            $socialRecord->familyMembers()->create([
                'first_name' => 'Carlos',
                'last_name' => 'Mamani Condori',
                'birth_date' => '1985-02-14',
                'gender' => Gender::MALE,
                'kinship' => Kinship::UNCLE,
                'education_level' => 'Técnico Superior',
                'profession' => 'Mecánico',
                'marital_status' => MaritalStatus::MARRIED,
                'phone' => '+591 71234569',
                'has_income' => true,
                'workplace' => 'Taller Automotriz Los Andes',
                'income_type' => IncomeType::MONTHLY,
                'total_income' => 3500.00,
            ]);
        }

        // =====================================================================
        // CHILD MEDICAL RECORD (1:1)
        // =====================================================================
        $child->medicalRecord()->firstOrCreate(
            ['child_id' => $child->id],
            [
                // Seguro
                'has_insurance' => true,
                'insurance_details' => 'Seguro Universal Materno Infantil (SUMI)',
                // Datos físicos
                'weight' => 14.5,
                'height' => 95.0,
                // Alergias
                'has_allergies' => false,
                'allergies_details' => null,
                // Tratamiento médico
                'has_medical_treatment' => false,
                'medical_treatment_details' => null,
                // Tratamiento psicológico
                'has_psychological_treatment' => false,
                'psychological_treatment_details' => null,
                // Déficits
                'has_deficit' => false,
                'deficit_auditory' => null,
                'deficit_visual' => null,
                'deficit_tactile' => null,
                'deficit_motor' => null,
                // Enfermedad
                'has_illness' => false,
                'illness_details' => null,
                // Documentos
                'medical_report_document' => null,
                'diagnosis_document' => null,
                // Otros
                'other_observations' => 'Niña sana con desarrollo normal para su edad. Vacunas al día.',
            ]
        );
    }
}
