<?php

namespace App\Containers\Monitoring\Child\Reports\Exports;

use App\Containers\Monitoring\Child\Models\Child;
use App\Containers\Monitoring\ChildVaccination\Tasks\GetChildVaccinationTrackingTask;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class VaccinationsSheet implements FromArray, WithTitle, WithEvents
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
        $data[] = ['REPORTE DE VACUNAS'];
        $data[] = ['Infante: ' . $this->child->full_name];
        
        // Encabezados
        $data[] = [
            '#',
            'Vacuna',
            'Dosis',
            'Edad Recomendada',
            'Fecha de Aplicación',
            'Edad al Aplicar',
            'Estado',
            'Observaciones',
        ];
        
        // Obtener datos de vacunación
        $trackingData = app(GetChildVaccinationTrackingTask::class)->run($this->child->id);
        $vaccinesData = $trackingData['vaccines'];
        $appliedVaccinations = $trackingData['applied_vaccinations'];
        
        $rowNumber = 1;
        
        foreach ($vaccinesData as $vaccine) {
            foreach ($vaccine->doses as $dose) {
                $childVaccination = $appliedVaccinations->get($dose->id);
                
                // Calcular edad al aplicar si existe la vacunación
                $ageAtApplication = null;
                if ($childVaccination) {
                    $ageAtApplication = $this->child->getAgeInMonthsAt($childVaccination->date_applied);
                }
                $ageAtApplicationReadable = $ageAtApplication !== null 
                    ? \App\Containers\Monitoring\Child\Models\Child::formatAgeFromMonths($ageAtApplication)
                    : '-';
                
                $data[] = [
                    $rowNumber++,
                    $vaccine->name,
                    $dose->dose_number,
                    $dose->recommended_age,
                    $childVaccination ? $childVaccination->date_applied->format('d/m/Y') : '-',
                    $ageAtApplicationReadable,
                    $childVaccination ? 'Aplicada' : ($dose->getStatusForAge($this->child->age_in_months ?? 0) === 'overdue' ? 'Vencida' : 'Pendiente'),
                    $childVaccination ? ($childVaccination->notes ?? '-') : '-',
                ];
            }
        }
        
        if ($vaccinesData->isEmpty()) {
            // $data[] = ['No hay vacunas registradas'];
        }
        
        return $data;
    }

    public function title(): string
    {
        return 'Vacunas';
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
                $sheet->getColumnDimension('B')->setWidth(30); // Vacuna
                $sheet->getColumnDimension('C')->setWidth(10); // Dosis
                $sheet->getColumnDimension('D')->setWidth(18); // Edad Recomendada
                $sheet->getColumnDimension('E')->setWidth(18); // Fecha de Aplicación
                $sheet->getColumnDimension('F')->setWidth(15); // Edad al Aplicar
                $sheet->getColumnDimension('G')->setWidth(12); // Estado
                $sheet->getColumnDimension('H')->setWidth(30); // Observaciones
                
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

