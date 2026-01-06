<?php

namespace App\Containers\Monitoring\Child\Reports\Exports;

use App\Containers\Monitoring\Child\Models\Child;
use App\Containers\Monitoring\NutritionalAssessment\Enums\NutritionalStatus;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class NutritionalAssessmentsSheet implements FromArray, WithTitle, WithEvents
{
    protected Child $child;

    public function __construct(Child $child)
    {
        $this->child = $child;
    }

    public function array(): array
    {
        $data = [];
        
        // Título
        $data[] = ['REPORTE DE EVALUACIONES NUTRICIONALES'];
        $data[] = ['Infante: ' . $this->child->full_name];
        
        // Encabezados
        $data[] = [
            '#',
            'Fecha',
            'Edad',
            'Peso (kg)',
            'Talla (cm)',
            'PC (cm)',
            'PB (cm)',
            'Z P/E',
            'Z T/E',
            'Z P/T',
            'Z IMC/E',
            'Estado P/E',
            'Estado T/E',
            'Estado P/T',
            'Estado IMC/E',
            'Observaciones',
        ];
        
        // Datos
        $assessments = $this->child->nutritionalAssessments()->orderBy('assessment_date', 'desc')->get();
        $rowNumber = 1;
        
        foreach ($assessments as $assessment) {
            $data[] = [
                $rowNumber++,
                $assessment->assessment_date->format('d/m/Y'),
                $assessment->age_readable,
                $assessment->weight ? number_format($assessment->weight, 2) : '-',
                $assessment->height ? number_format($assessment->height, 2) : '-',
                $assessment->head_circumference ? number_format($assessment->head_circumference, 2) : '-',
                $assessment->arm_circumference ? number_format($assessment->arm_circumference, 2) : '-',
                $assessment->z_weight_age !== null ? number_format($assessment->z_weight_age, 2) : '-',
                $assessment->z_height_age !== null ? number_format($assessment->z_height_age, 2) : '-',
                $assessment->z_weight_height !== null ? number_format($assessment->z_weight_height, 2) : '-',
                $assessment->z_bmi_age !== null ? number_format($assessment->z_bmi_age, 2) : '-',
                $assessment->status_weight_age ? $assessment->status_weight_age->label() : '-',
                $assessment->status_height_age ? $assessment->status_height_age->label() : '-',
                $assessment->status_weight_height ? $assessment->status_weight_height->label() : '-',
                $assessment->status_bmi_age ? $assessment->status_bmi_age->label() : '-',
                $assessment->observations ?? '-',
            ];
        }
        
        if ($assessments->isEmpty()) {
            $data[] = ['No hay evaluaciones nutricionales registradas'];
        }
        
        return $data;
    }

    public function title(): string
    {
        return 'Evaluaciones Nutricionales';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();
                
                // Fusionar celdas del título
                $sheet->mergeCells('A1:' . $lastColumn . '1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                
                // Fusionar celdas del nombre del infante
                $sheet->mergeCells('A2:' . $lastColumn . '2');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                
                // Formatear encabezados (fila 3)
                $sheet->getStyle('A3:' . $lastColumn . '3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E3F2FD'],
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);
                
                // Ajustar ancho de columnas
                $sheet->getColumnDimension('A')->setWidth(6);  // #
                $sheet->getColumnDimension('B')->setWidth(12); // Fecha
                $sheet->getColumnDimension('C')->setWidth(12); // Edad
                $sheet->getColumnDimension('D')->setWidth(12); // Peso
                $sheet->getColumnDimension('E')->setWidth(12); // Talla
                $sheet->getColumnDimension('F')->setWidth(10); // PC
                $sheet->getColumnDimension('G')->setWidth(10); // PB
                $sheet->getColumnDimension('H')->setWidth(10); // Z P/E
                $sheet->getColumnDimension('I')->setWidth(10); // Z T/E
                $sheet->getColumnDimension('J')->setWidth(10); // Z P/T
                $sheet->getColumnDimension('K')->setWidth(10); // Z IMC/E
                $sheet->getColumnDimension('L')->setWidth(15); // Estado P/E
                $sheet->getColumnDimension('M')->setWidth(15); // Estado T/E
                $sheet->getColumnDimension('N')->setWidth(15); // Estado P/T
                $sheet->getColumnDimension('O')->setWidth(15); // Estado IMC/E
                $sheet->getColumnDimension('P')->setWidth(30); // Observaciones
                
                // Aplicar bordes a los datos (solo a las filas de datos, no a los encabezados)
                if ($lastRow > 3) {
                    $sheet->getStyle('A4:' . $lastColumn . $lastRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                        ],
                        'alignment' => ['vertical' => Alignment::VERTICAL_TOP],
                    ]);
                    // Asegurar que los encabezados mantengan su estilo
                    $sheet->getStyle('A3:' . $lastColumn . '3')->applyFromArray([
                        'font' => ['bold' => true, 'size' => 11],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E3F2FD'],
                        ],
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                        ],
                    ]);
                }
                
                // Congelar paneles
                $sheet->freezePane('A4');
            },
        ];
    }
}

