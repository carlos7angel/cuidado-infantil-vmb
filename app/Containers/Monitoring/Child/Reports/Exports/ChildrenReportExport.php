<?php

namespace App\Containers\Monitoring\Child\Reports\Exports;

use App\Containers\AppSection\Settings\Models\Settings;
use App\Containers\Monitoring\ChildEnrollment\Models\ChildEnrollment;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ChildrenReportExport implements FromArray, WithTitle, WithEvents
{
    protected $enrollments;

    public function __construct($childcareCenterId = null)
    {
        $query = ChildEnrollment::with([
            'child.medicalRecord',
            'child.socialRecord',
            'childcareCenter',
            'room',
        ])->orderByDesc('enrollment_date');

        if ($childcareCenterId) {
            $query->where('childcare_center_id', $childcareCenterId);
        }

        $this->enrollments = $query->get();
    }

    public function array(): array
    {
        $data = [];

        $data[] = ['REPORTE DE INFANTES INSCRITOS'];

        $municipality = Settings::get('servidor_municipio', '');
        $data[] = ['Fecha de Generación: ' . now()->format('d/m/Y H:i:s')];
        $data[] = ['Municipio/Departamento: ' . $municipality];

        $data[] = [''];

        $data[] = [
            '#',
            'Apellido Paterno',
            'Apellido Materno',
            'Nombre(s)',
            'Fecha de Nacimiento',
            'Edad',
            'Género',
            'Idioma',
            'Departamento',
            'Ciudad',
            'Municipio',
            'Dirección',
            'Centro Infantil',
            'Sala/Grupo',
            'Estado Inscripción',
            'Fecha Inscripción',
            'Fecha Retiro',
            'Observaciones Inscripción',
            'Peso (kg)',
            'Talla (cm)',
            'Tiene Seguro Médico',
            'Detalle Seguro Médico',
            'Tiene Alergias',
            'Detalle Alergias',
            'Tiene Tratamiento Médico',
            'Detalle Tratamiento Médico',
            'Tiene Tratamiento Psicológico',
            'Detalle Tratamiento Psicológico',
            'Tiene Déficits',
            'Déficits Detalle',
            'Tiene Enfermedades',
            'Detalle Enfermedades',
            'Habilidades Destacadas',
            'Problemas de Nutrición',
            'Otras Observaciones Médicas',
            'Infante a cargo de',
            'Total Egresos (Bs.)',
            'Tipo de Vivienda',
            'Tenencia de Vivienda',
            'Material de Paredes',
            'Material de Piso',
            'Acabado',
            'Número de Dormitorios',
            'Ambientes',
            'Servicios Públicos',
            'Tipo de Transporte',
            'Tiempo de Viaje',
            'Antecedentes de Incidentes',
            'Mascotas',
            'Doc: Carta Solicitud Admisión',
            'Doc: Compromiso',
            'Doc: Certificado Nacimiento',
            'Doc: Carnet Vacunas',
            'Doc: Doc. Identidad Padre/Madre',
            'Doc: Cert. Constancia Laboral',
            'Doc: Recibo Agua y Luz',
            'Doc: Croquis Domicilio',
            'Doc: Constancia de Vivienda',
            'Doc: Autorización de Recojo',
        ];

        $rowNumber = 1;

        foreach ($this->enrollments as $enrollment) {
            $child = $enrollment->child;
            $medical = $child?->medicalRecord;
            $social = $child?->socialRecord;

            $birthDate = $child && $child->birth_date
                ? $child->birth_date->format('d/m/Y')
                : '';

            $ageReadable = $child?->age_readable ?? '';

            $gender = $child && $child->gender
                ? $child->gender->label()
                : '';

            $statusLabel = $enrollment->status
                ? ucfirst($enrollment->status->value)
                : '';

            $enrollmentDate = $enrollment->enrollment_date
                ? $enrollment->enrollment_date->format('d/m/Y')
                : '';

            $withdrawalDate = $enrollment->withdrawal_date
                ? $enrollment->withdrawal_date->format('d/m/Y')
                : '';

            $hasInsurance = $medical && $medical->has_insurance ? 'Sí' : 'No';
            $insuranceDetails = $medical && $medical->has_insurance
                ? ($medical->insurance_details ?: 'Sí')
                : '';

            $hasAllergies = $medical && $medical->has_allergies ? 'Sí' : 'No';
            $allergiesDetails = $medical && $medical->has_allergies
                ? ($medical->allergies_details ?: 'Sí')
                : '';

            $hasMedicalTreatment = $medical && $medical->has_medical_treatment ? 'Sí' : 'No';
            $medicalTreatmentDetails = $medical && $medical->has_medical_treatment
                ? ($medical->medical_treatment_details ?: 'Sí')
                : '';

            $hasPsychologicalTreatment = $medical && $medical->has_psychological_treatment ? 'Sí' : 'No';
            $psychologicalTreatmentDetails = $medical && $medical->has_psychological_treatment
                ? ($medical->psychological_treatment_details ?: 'Sí')
                : '';

            $hasDeficits = $medical && $medical->has_deficit && $medical->hasAnyDeficit();
            $deficitsTextParts = [];
            if ($hasDeficits) {
                if ($medical->deficit_auditory) {
                    $deficitsTextParts[] = 'Auditivo';
                }
                if ($medical->deficit_visual) {
                    $deficitsTextParts[] = 'Visual';
                }
                if ($medical->deficit_tactile) {
                    $deficitsTextParts[] = 'Táctil';
                }
                if ($medical->deficit_motor) {
                    $deficitsTextParts[] = 'Motor';
                }
            }
            $deficitsFlag = $hasDeficits ? 'Sí' : 'No';
            $deficitsText = $hasDeficits && !empty($deficitsTextParts)
                ? implode(', ', $deficitsTextParts)
                : '';

            $hasIllness = $medical && $medical->has_illness ? 'Sí' : 'No';
            $illnessDetails = $medical && $medical->has_illness
                ? ($medical->illness_details ?: 'Sí')
                : '';

            $otherMedicalObservations = $medical && $medical->other_observations
                ? $medical->other_observations
                : '';

            $guardianType = $social && $social->guardian_type
                ? $social->guardian_type->label()
                : '';

            $totalExpenses = $social
                ? number_format((float) $social->total_expenses, 2)
                : '';

            $housingType = $social && $social->housing_type
                ? $social->housing_type->label()
                : '';

            $housingTenure = $social && $social->housing_tenure
                ? $social->housing_tenure->label()
                : '';

            $housingWallMaterial = $social && $social->housing_wall_material
                ? $social->housing_wall_material->label()
                : '';

            $housingFloorMaterial = $social && $social->housing_floor_material
                ? $social->housing_floor_material->label()
                : '';

            $housingFinish = $social && $social->housing_finish
                ? $social->housing_finish->label()
                : '';

            $housingBedrooms = $social && $social->housing_bedrooms !== null
                ? $social->housing_bedrooms
                : '';

            $housingRooms = '';
            if ($social && is_array($social->housing_rooms) && count($social->housing_rooms) > 0) {
                $housingRooms = implode(', ', array_map('ucfirst', $social->housing_rooms));
            }

            $housingUtilities = '';
            if ($social && is_array($social->housing_utilities) && count($social->housing_utilities) > 0) {
                $items = array_map(function ($util) {
                    $enum = \App\Containers\Monitoring\Child\Enums\HousingUtility::tryFrom($util);
                    return $enum ? $enum->label() : ucfirst(str_replace('_', ' ', $util));
                }, $social->housing_utilities);

                $housingUtilities = implode(', ', $items);
            }

            $transportType = $social && $social->transport_type
                ? $social->transport_type->label()
                : '';

            $travelTime = $social && $social->travel_time
                ? $social->travel_time->label()
                : '';

            $admissionRequestFile = $enrollment->file_admission_request ? 'Sí' : 'No';
            $commitmentFile = $enrollment->file_commitment ? 'Sí' : 'No';
            $birthCertificateFile = $enrollment->file_birth_certificate ? 'Sí' : 'No';
            $vaccinationCardFile = $enrollment->file_vaccination_card ? 'Sí' : 'No';
            $parentIdFile = $enrollment->file_parent_id ? 'Sí' : 'No';
            $workCertificateFile = $enrollment->file_work_certificate ? 'Sí' : 'No';
            $utilityBillFile = $enrollment->file_utility_bill ? 'Sí' : 'No';
            $homeSketchFile = $enrollment->file_home_sketch ? 'Sí' : 'No';
            $residenceCertificateFile = $enrollment->file_residence_certificate ? 'Sí' : 'No';
            $pickupAuthorizationFile = $enrollment->file_pickup_authorization ? 'Sí' : 'No';

            $data[] = [
                $rowNumber++,
                $child->paternal_last_name ?? '',
                $child->maternal_last_name ?? '',
                $child->first_name ?? '',
                $birthDate,
                $ageReadable,
                $gender,
                $child->language ?? '',
                $child->state ?? '',
                $child->city ?? '',
                $child->address ?? '',
                $enrollment->childcareCenter?->name ?? '',
                $enrollment->room?->name ?? '',
                $statusLabel,
                $enrollmentDate,
                $withdrawalDate,
                $enrollment->observations ?? '',
                $medical && $medical->weight !== null ? number_format((float) $medical->weight, 2) : '',
                $medical && $medical->height !== null ? number_format((float) $medical->height, 2) : '',
                $hasInsurance,
                $insuranceDetails,
                $hasAllergies,
                $allergiesDetails,
                $hasMedicalTreatment,
                $medicalTreatmentDetails,
                $hasPsychologicalTreatment,
                $psychologicalTreatmentDetails,
                $deficitsFlag,
                $deficitsText,
                $hasIllness,
                $illnessDetails,
                $medical && $medical->outstanding_skills ? $medical->outstanding_skills : '',
                $medical && $medical->nutritional_problems ? $medical->nutritional_problems : '',
                $otherMedicalObservations,
                $guardianType,
                $totalExpenses,
                $housingType,
                $housingTenure,
                $housingWallMaterial,
                $housingFloorMaterial,
                $housingFinish,
                $housingBedrooms,
                $housingRooms,
                $housingUtilities,
                $transportType,
                $travelTime,
                $social && $social->incident_history ? $social->incident_history : '',
                $social && $social->pets ? $social->pets : '',
                $admissionRequestFile,
                $commitmentFile,
                $birthCertificateFile,
                $vaccinationCardFile,
                $parentIdFile,
                $workCertificateFile,
                $utilityBillFile,
                $homeSketchFile,
                $residenceCertificateFile,
                $pickupAuthorizationFile,
            ];
        }

        if ($this->enrollments->isEmpty()) {
            $data[] = ['No hay infantes inscritos registrados'];
        }

        return $data;
    }

    public function title(): string
    {
        return 'Infantes';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                $sheet->mergeCells('A1:' . $lastColumn . '1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);

                $sheet->mergeCells('A2:' . $lastColumn . '2');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);

                $sheet->mergeCells('A3:' . $lastColumn . '3');
                $sheet->getStyle('A3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);

                $sheet->getStyle('A5:' . $lastColumn . '5')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E8F5E9'],
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);

                if ($lastRow > 5) {
                    $sheet->getStyle('A6:' . $lastColumn . $lastRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                        ],
                        'alignment' => ['vertical' => Alignment::VERTICAL_TOP],
                    ]);

                    $sheet->getStyle('A6:A' . $lastRow)->applyFromArray([
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);
                }

                for ($col = 'A'; $col <= $lastColumn; $col++) {
                    $sheet->getColumnDimension($col)->setWidth(20);
                }

                $sheet->freezePane('A6');
            },
        ];
    }
}
